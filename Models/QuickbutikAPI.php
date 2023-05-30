<?php
class QuickbutikAPI
{
	private string $endPoint = "https://api.quickbutik.com/v1";
	private string $apiKey = "d6nD79jLU3yB8#GJHoLFC8pwhcQ-Oo()";
	private string $authentication;
	private array $headers;
	private string $lastError;

	public function __construct()
	{
		$this->authentication = $this->apiKey . ":" . $this->apiKey;
		$this->headers = [
			"Authorization: Basic " . base64_encode($this->authentication)
		];
	}

	/**
	 * Call to Quickbutik API using GET method.
	 * Abort on error and save to $this->lastError
	 * @param string $target Target for API call, ie. "orders?order_id=12345"
	 * @return null|string JSON encoded string or null
	 */
	public function apiGet(string $target): ?string
	{
		try {
			$this->lastError = "";
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_URL, "$this->endPoint/$target");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			if (curl_error($curl)) {
				throw new Exception(curl_error($curl));
			}
			if (curl_errno($curl)) {
				throw new Exception(curl_errno($curl));
			}
			return curl_exec($curl) ?: null;
		} catch (Exception $e) {
			$this->lastError = $e;
			return null;
		} finally {
			curl_close($curl);
		}
	}

	/**
	 * Get error for last API call
	 */
	public function getLastError(): string
	{
		return $this->lastError;
	}
}
