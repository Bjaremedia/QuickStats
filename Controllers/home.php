<?php
require_once ROOT . '/Models/Statistics.php';

class Home
{
	private ?string $pageContent;
	private Statistics $statistics;
	private bool $isEndPoint = false;

	public function __construct()
	{
		$this->statistics = new Statistics;
		$this->isEndPoint = $this->checkIfCallToEndPoint();
		if ($this->isEndPoint) {
			header('Content-Type: application/json; charset=utf-8');
			echo $this->renderResultAsJson();
			return;
		}
		$this->pageContent = $this->getPageContent();
		$this->renderPage();
	}

	/**
	 * Render start page, template use $pageContent if not null
	 */
	private function renderPage()
	{
		require_once ROOT . '/Views/home.php';
	}

	/**
	 * Bad practice to copy but I'm a little short on time and got
	 * a somewhat bad approach. Anyways, just print result as JSON.
	 * @return null|string JSON encoded result
	 */
	private function renderResultAsJson(): ?string
	{
		if (!isset($_GET['action'])) {
			return null;
		}
		$action = trim((string) $_GET['action'] ?? '');
		switch ($action) {
			case "avgOrderValue":
				$orderValue = new stdClass();
				$orderValue->averageOrderValue = $this->statistics->getAverageOrderValue();
				return json_encode($orderValue);
			case "pop3Products":
				$top3Products = $this->statistics->getPopularProducts(3);
				return json_encode($top3Products);
			default:
				return null;
		}
	}

	/**
	 * Check if should act as API end point
	 */
	private function checkIfCallToEndPoint(): bool
	{
		if (!isset($_GET['endpoint'])) {
			return false;
		}
		return trim($_GET['endpoint']) === "1";
	}

	/**
	 * Simple routing for $_GET actions
	 * @return null|string
	 */
	private function getPageContent(): ?string
	{
		if (!isset($_GET['action'])) {
			return null;
		}
		$action = trim((string) $_GET['action'] ?? '');
		switch ($action) {
			case "avgOrderValue":
				return $this->averageOrderValue();
			case "pop3Products":
				return $this->top3Products();
			default:
				return null;
		}
	}

	/**
	 * Get average order value
	 * @return string HTML
	 */
	private function averageOrderValue(): string
	{
		$averageOrderValue = $this->statistics->getAverageOrderValue();
		if (!$averageOrderValue) {
			return "<p>Kunde inte hämta genomsnittligt ordervärde. Försök igen senare.</p>";
		}
		return "<p>Genomsnittligt ordervärde är: $averageOrderValue</p>";
	}

	/**
	 * Get list of top 3 products
	 * @return string HTML
	 */
	private function top3Products(): string
	{
		$topProducts = $this->statistics->getPopularProducts(3);
		if (!$topProducts) {
			return "<p>Kunde inte hämta de tre mest populära produkterna. Försök igen senare.</p>";
		}
		$productList = '<ol>';
		foreach ($topProducts as $product) {
			$productList .= "<li>{$product->title}, besök: {$product->visits}</li>";
		}
		$productList .= '</ol>';
		return $productList;
	}
}
