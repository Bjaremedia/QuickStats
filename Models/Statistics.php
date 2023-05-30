<?php
require_once ROOT . '/Models/QuickbutikAPI.php';

class Statistics
{
	private array $orders = [];
	private array $products = [];

	/**
	 * Populate orders array, then calculate average order value
	 * @return null|float
	 */
	public function getAverageOrderValue(): ?float
	{
		if (!$this->orders) {
			$quickbutikAPI = new QuickbutikAPI();
			if (!$orders = $quickbutikAPI->apiGet('orders')) {
				return null;
			}
			$this->orders = json_decode($orders);
		}

		$orderAmountSum = 0;
		foreach ($this->orders as $order) {
			$orderAmountSum += $order->total_amount;
		}

		return $orderAmountSum / count($this->orders);
	}

	/**
	 * Populate products array, sort by visited, return X products
	 * @param int $numberOfProducts
	 * @return null|array
	 */
	public function getPopularProducts(int $numberOfProducts): ?array
	{
		if (!$this->products) {
			$quickbutikAPI = new QuickbutikAPI();
			if (!$products = $quickbutikAPI->apiGet('products?include_details=true')) {
				return null;
			}
			$this->products = json_decode($products);
		}

		$popularProducts = $this->products;
		usort($popularProducts, function ($a, $b) {
			return $b->visits <=> $a->visits;
		});

		return array_slice($popularProducts, 0, $numberOfProducts);
	}
}
