# Ansible

[Return to Ansible main page](Ansible.md)

## Local

### Requirements

- Ansible *5.1.0* (you can find the right way, based on your OS in this [guide][ansible_installation_docs])

### Hosts
Open your ansible/hosts.ini file. Here you can find three server groups: servers, webservers and dbservers.

- The servers group is generic, and it is used only for common tasks between all the servers (system updates, application updates or ssh configuration).
- The webservers group is responsible for the creation of the web server servers 😀. Yes, servers, because being a group you can specify multiple machines to be used in a scalable way.
- The dbservers group is responsible for the creation of the database servers. As for the webservers, also these group could accept more machine.

So, just add all your server with all the information needed to connect to the server via ssh, you can find more on this [guide][ansible_host_parameters]. For example, let's create a server using vagrant on your project root/.vagrant folder:

    [vagrant_server]
    192.168.56.10 ansible_user=vagrant ansible_ssh_pass=vagrant ansible_ssh_private_key_file=./.vagrant/.vagrant/machines/default/virtualbox/private_key
    
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
By default, all the commands in this doc will use the ansible/vars/.password file as decryption password. This file should just contain the password used for the vault.yml file.  The default password of the vault.yml file is `password`😬. It is recommended to change this password and be aware to leave the file .password out from the repository.
You can view/edit this variable simply from your terminal with the ansible-vault command:

    ansible-vault view ansible/vars/vault.yml --vault-password-file ansible/vars/.password # View vault vars
    
    ansible-vault edit ansible/vars/vault.yml --vault-password-file ansible/vars/.password # Edit vault vars

### Roles

As anticipated earlier, the project's playbook makes use of several Ansible's third-party roles. Before launching the provision it is necessary to install them with the following commands:

    ansible-galaxy install geerlingguy.postgresql
    ansible-galaxy install geerlingguy.php-versions
    ansible-galaxy install geerlingguy.php
    ansible-galaxy install geerlingguy.apache-php-fpm
    ansible-galaxy install geerlingguy.php-pgsql
    ansible-galaxy install geerlingguy.apache
    ansible-galaxy install geerlingguy.nodejs

### Run provision

When you are done modifying the hosts and variables according to your needs, it is time to run the playbook to prepare your servers. To do it just launch the playbook specifying the correct hosts.ini file and the ask-vault-password option to unlock the vault with your secret password.

    ansible-playbook ansible/playbook.yml -i ansible/hosts.ini --vault-password-file ansible/vars/.password

If you want to run provision only for dbservers or webservers just add the tags option with the values web or database.

    ansible-playbook ansible/playbook.yml -i ansible/hosts.ini --ask-vault-password -t database

[ansible_installation_docs]: https://docs.ansible.com/ansible/latest/installation_guide/intro_installation.html
[ansible_host_parameters]:https://docs.ansible.com/ansible/2.6/user_guide/intro_inventory.html#list-of-behavioral-inventory-parameters
