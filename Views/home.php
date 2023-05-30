<html>

<head>
	<title>QuickStats</title>
</head>

<body>
	<h1>QuickStats - Quickbutik kodtest</h1>
	<hr />

	<h2>Endpoints for API</h2>
	<nav>
		<a href="?endpoint=1&action=avgOrderValue">Genomsnittligt ordervärde</a> |
		<a href="?endpoint=1&action=pop3Products">Tre mest populära produkter</a>
	</nav>
	<hr />

	<h2>Visa resultat från anrop</h2>
	<nav>
		<a href="?action=avgOrderValue">Genomsnittligt ordervärde</a> |
		<a href="?action=pop3Products">Tre mest populära produkter</a>
	</nav>
	<br />

	<?= $this->pageContent ?>

</body>

</html>