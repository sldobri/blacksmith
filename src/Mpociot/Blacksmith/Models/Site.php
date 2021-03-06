<?php

namespace Mpociot\Blacksmith\Models;

class Site extends ForgeModel
{
    protected $id;
    protected $server_id;
    protected $name;
    protected $repository;
    protected $directory;
    protected $wildcards;
    protected $displayable_project_type;
    protected $secured;
    protected $has_app_installed;

    /**
     * Return the configured .env data
     * @return string
     */
    public function getEnvironment()
    {
        return $this->browser->getContent('https://forge.laravel.com/servers/'.$this->server_id.'/sites/'.$this->id.'/environment', true);
    }

    /**
     * Install an application on this site.
     *
     * @param  string  $repository The repository user/name to install
     * @param  string  $provider   Application provider. Can be 'github', 'bitbucket' or 'custom'
     * @param  string  $branch     The branch to deploy
     * @param  boolean $composer   Install composer dependencies
     * @param  boolean $migrate    Migrate
     * @return mixed
     */
    public function installApp($repository, $provider = 'github', $branch = 'master', $composer = true, $migrate = false)
    {
        if (! $this->has_app_installed) {
            return $this->browser->postContent('https://forge.laravel.com/servers/'.$this->server_id.'/sites/'.$this->id.'/project', [
                'repository' => $repository,
                'provider' => $provider,
                'branch' => $branch,
                'composer' => $composer,
                'migrate' => $migrate,
            ]);
        }
        return false;
    }

    /**
     * Deploy the current installed application on this site.
     * 
     * @return mixed
     */
    public function deploy()
    {
        if ($this->has_app_installed) {
            return $this->browser->postContent('https://forge.laravel.com/servers/'.$this->server_id.'/sites/'.$this->id.'/deploy/now', []);
        }
        return false;
    }

    /**
     * Get the last deployment log on this site.
     * 
     * @return mixed
     */
    public function deployLog()
    {
        if ($this->has_app_installed) {
            return $this->browser->postContent('https://forge.laravel.com/servers/'.$this->server_id.'/sites/'.$this->id.'/deploy/log', [], true);
        }
        return '';
    }
}
