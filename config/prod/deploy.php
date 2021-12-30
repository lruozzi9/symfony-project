<?php

use EasyCorp\Bundle\EasyDeployBundle\Configuration\DefaultConfiguration;
use EasyCorp\Bundle\EasyDeployBundle\Configuration\Option;
use EasyCorp\Bundle\EasyDeployBundle\Deployer\DefaultDeployer;

return new class extends DefaultDeployer {
    public function configure(): DefaultConfiguration
    {
        return $this->getConfigBuilder()
            ->server('vagrant@symfony-project.it')
            ->deployDir('/var/www/symfony-project')
            ->repositoryUrl('git@github.com:lruozzi9/symfony-project.git')
            ->repositoryBranch('master')
            ->sharedFilesAndDirs(['.env.local', 'var/log']);
    }

    /**
     * Needed for https://github.com/EasyCorp/easy-deploy-bundle/issues/78
     */
    public function beforePreparing(): void
    {
        $this->runRemote('cp {{ deploy_dir }}/repo/.env {{ project_dir }}/.env');
    }

    public function beforePublishing(): void
    {
        $this->log('Remote yarn');
        $this->runRemote('yarn install');
        $this->runRemote('yarn run build');

        $this->log('Remote composer dump env prod');
        $this->runRemote(sprintf('%s dump-env prod', $this->getConfig(Option::remoteComposerBinaryPath)));

        $this->log('Remote migration');
        $this->runRemote('{{ console_bin }} doctrine:migration:migrate --no-interaction --allow-no-migration');

        $this->log('Remote cache clear');
        $this->runRemote('{{ console_bin }} cache:clear');

        $this->log('Remote cache warmpup');
        $this->runRemote('{{ console_bin }} cache:warmup');
    }
};
