<?php
/**
 * PHP Version 5.5
 * @category Library
 * @package ChangeLog
 * @author Steve "uru" West <steven.david.west@gmail.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @link https://github.com/stevewest/changelog
 */

namespace ChangeLog\IO;

use ChangeLog\AbstractIO;
use Milo\Github\Api;
use Milo\Github\OAuth\Token;
use InvalidArgumentException;
use stdClass;

/**
 * Allows change log files to be read from GitHub and committed back.
 *
 * This class has the following config options
 * - repo: "<user>/<repo>" The full username and repo name to use.
 * - file: Path to the file from the root of the repo
 * - token: GitHub API token, can be generated under account -> applications
 * - commit_message: Optional commit message, used when setContent() is called.
 *
 * The token must have the "repo" or "public_repo" permission depending on the visibility of the repo.
 */
class GitHub extends AbstractIO
{

	/**
	 * @var Api
	 */
	protected $api;

	protected $configDefaults = [
		'commit_message' => 'Updates change log.',
		'line_separator' => "\n",
	];

	/**
	 * Gets an active connection to the GitHub api.
	 *
	 * @return Api
	 */
	protected function getApi()
	{
		if ($this->api === null)
		{
			$this->createApiInstance();
		}

		return $this->api;
	}

	/**
	 * Creates a new instance of the API library to use later.
	 *
	 * @throws InvalidArgumentException
	 */
	protected function createApiInstance()
	{
		$configToken = $this->getConfig('token');

		if ($configToken === null)
		{
			throw new InvalidArgumentException('API token has not been set in the config.');
		}

		$token = new Token($configToken);
		$this->api = new Api();
		$this->api->setToken($token);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getContent()
	{
		$content = $this->requestFile();

		return explode(
			$this->getConfig('line_separator'),
			base64_decode($content->content)
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setContent($content)
	{
		// Request the file so we can get its sha
		$fileInfo = $this->requestFile();

		$encodedContent = base64_encode($content);
		$data = [
			'message' => $this->getConfig('commit_message'),
			'content' => $encodedContent,
			'sha' => $fileInfo->sha,
		];

		$api = $this->getApi();
		$response = $api->put($this->getApiUrl(), $data);
		// Parse the response so an exception is thrown if anything goes funky
		$api->decode($response);
	}

	/**
	 * Gets a URL for the GitHub api for our file.
	 *
	 * @return string
	 */
	protected function getApiUrl()
	{
		$repo = $this->getConfig('repo');
		$file = $this->getConfig('file');

		$url = "/repos/$repo/contents/$file";
		return $url;
	}

	/**
	 * @return stdClass
	 */
	protected function requestFile()
	{
		$api = $this->getApi();
		$response = $api->get($this->getApiUrl());
		$content = $api->decode($response);
		return $content;
	}

}
