<?php

namespace Effectra\Core\Utils;

/**
 * Class GitHubRepositoryManager
 *
 * This class provides a way to manipulate the composer.json file and execute necessary commands
 * to include a GitHub repository as a dependency in a PHP project.
 */
class GitHubRepositoryManager {
    /**
     * Path to the composer.json file.
     *
     * @var string
     */
    private $composerJsonPath;

    /**
     * Initialize the GitHubRepositoryManager instance.
     *
     * @param string $composerJsonPath Path to the composer.json file.
     */
    public function __construct(string $composerJsonPath) {
        $this->composerJsonPath = $composerJsonPath;
    }

    /**
     * Run the necessary commands to include a GitHub repository as a dependency in the PHP project.
     *
     * @param string $repository The GitHub repository in the format "username/private-repo".
     * @param string $accessToken (Optional) Personal access token for private repositories.
     * @return void
     */
    public function run(string $repository, string $accessToken = '') {
        $this->updateComposerJson($repository);
        $this->configureGitHubToken($accessToken);
        $this->executeComposerInstall();
    }

    /**
     * Update the composer.json file with the specified repository details.
     *
     * @param string $repository The GitHub repository in the format "username/private-repo".
     * @return void
     */
    private function updateComposerJson(string $repository) {
        $composerJson = json_decode(file_get_contents($this->composerJsonPath), true);

        // Update require section
        $composerJson['require'] = [
            'vendor/package' => 'dev-master'
        ];

        // Update repositories section
        $composerJson['repositories'] = [
            [
                'type' => 'vcs',
                'url' => "https://github.com/{$repository}.git"
            ]
        ];

        file_put_contents($this->composerJsonPath, json_encode($composerJson, JSON_PRETTY_PRINT));
    }

    /**
     * Configure the GitHub access token in Composer if provided.
     *
     * @param string $accessToken The personal access token for private repositories.
     * @return void
     */
    private function configureGitHubToken(string $accessToken) {
        if (!empty($accessToken)) {
            exec("composer config --global github-oauth.github.com {$accessToken}");
        }
    }

    /**
     * Execute the `composer install` command.
     *
     * @return void
     */
    private function executeComposerInstall() {
        exec("composer install");
    }
}
