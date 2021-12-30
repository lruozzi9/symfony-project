# Ansible

[Return to Ansible main page](Ansible.md)

## Docker

### Requirements

- Docker ([install guide][docker_installation_docs])

### Hosts
Open your ansible/hosts.ini file. Here you can find three server groups: servers, webservers and dbservers.

- The servers group is generic, and it is used only for common tasks between all the servers (system updates, application updates or ssh configuration).
- The webservers group is responsible for the creation of the web server servers 😀. Yes, servers, because being a group you can specify multiple machines to be used in a scalable way.
- The dbservers group is responsible for the creation of the database servers. As for the webservers, also these group could accept more machine.

So, just add all your server with all the information needed to connect to the server via ssh, you can find more on this [guide][ansible_host_parameters]. For example, let's create a server using vagrant on your project root/.vagrant folder:

    [vagrant_server]
    192.168.56.10 ansible_user=vagrant ansible_ssh_pass=vagrant
    
    [webservers:children]
    vagrant_server
      
    [dbservers:children]
    vagrant_server
      
    [servers:children]
    webservers
    dbservers

Now, your hosts file is ready! But, what if you have more server for different environment, as for example staging and production? Just copy the file hosts.ini and name that file with for example, staging.ini or production.ini and set your right servers parameters. Later, we will se how to use these files.


### Vars

Take a look at the ansible/vars/vars.yml file. This file is where all the configurations of the servers are managed. By default, that file will contain variables for simple projects, like *host_server_name*, *project_root_dir*, *postgresql_databases*, *apache_vhosts*, *php_version* and *php_date_timezone*. If you need more complex setups check the readme of the ansible roles used.

### Vault

In the previous section probably you encountered some special variables that refers to other vault variables not contained in the vars file like, for example , the postgresql_users.password var. This is intentionally done because it is secret var to hide preferably also in the repository.
By default, all the commands in this doc will use the ansible/vars/.password file as decryption password. This file should just contain the password used for the vault.yml file. The default password of the vault.yml file is `password`😬. It is recommended to change this password and be aware to leave the file .password out from the repository.
You can view/edit this variable simply from your browser without install the ansible-vault command by using the [Ansible Vault Tool](https://ansible-vault-tool.com/).

### Run provision

First, you should check that the agent ssh-add contains the necessary ssh private key to connect to the remote servers, use this command to list all available keys.

	ssh-add -l

If the key/s for your/s server/s is/are not displayed, launch the following command with the path of your key file. This is an example for a vagrant machine.

	ssh-add .vagrant/.vagrant/machines/default/virtualbox/private_key

Let's check again with `ssh-add -l` until it is successfully displayed.

Now create the docker image containings all of the necessary to run the provision.

    docker build -t symfony-project_ansible-image ansible/.

If you encounter some problems it could be usefully to remove e recreate the image without cache by using this command.

	docker rmi symfony-project_ansible-image && docker build --no-cache -t symfony-project_ansible-image ansible/.

Now, we have an image ready to be used from a container, so just use it. We will use the docker run command, it will create a temporary container with two volumes: one for the ssh agent and another for the ansible recipe. We will also set two env vars to define the SSH AUTH SOCK of the mounted volume and the ANSIBLE HOST KEY CHECKING to disable known hosts check for the ssh connection. Finally, we specify our previously created image to use and the command to run

	docker run --rm -t -i \
	--name "symfony-project_ansible" \
	-v /run/host-services/ssh-auth.sock:/ssh-agent \
	-e SSH_AUTH_SOCK="/ssh-agent" \
	-e ANSIBLE_HOST_KEY_CHECKING=False \
	-v symfony-project_ansible-volume:/root/.ansible \
	symfony-project_ansible-image:latest \
	ansible-playbook playbook.yml -i hosts.ini --vault-password-file vars/.password

If you want to run provision only for dbservers or webservers just add the tags option with the values web or database.

    docker run --rm -t -i \
	--name "symfony-project_ansible" \
	-v /run/host-services/ssh-auth.sock:/ssh-agent \
	-e SSH_AUTH_SOCK="/ssh-agent" \
	-e ANSIBLE_HOST_KEY_CHECKING=False \
	-v symfony-project_ansible-volume:/root/.ansible \
	symfony-project_ansible-image:latest \
	ansible-playbook playbook.yml -i hosts.ini --vault-password-file vars/.password -t database

[ansible_host_parameters]:https://docs.ansible.com/ansible/2.6/user_guide/intro_inventory.html#list-of-behavioral-inventory-parameters
[docker_installation_docs]:https://docs.docker.com/get-docker/
