<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="<?= $this->security->get_csrf_token_name(); ?>" content="<?= $this->security->get_csrf_hash(); ?>">

	<title>Reporting - <?= $title ?></title>

	<link href="<?= base_url('assets/css/reporting/main.css') ?>" rel="stylesheet" />

	<?php if (file_exists(FCPATH . 'assets/css/reporting/' . $this->router->fetch_class() . '.css')) :
		echo "<link href='" . base_url('assets/css/reporting/' . $this->router->fetch_class() . '.css') . "' rel='stylesheet' />";
	endif ?>
</head>

<body class="fix-header fix-sidebar card-no-border" data-title="<?= $title ?>" data-route="<?= $route ?>" data-controller="<?= $this->router->fetch_class() ?>" data-method="<?= $this->router->fetch_method() ?>">

	<div id="page-wrapper">
		<div id="tools-section">
			<button class="print">print</button>
		</div>
		
		<?= $this->load->view($pageReportContent); ?>
	</div>

	<script>
		baseUrl = (path = "") => {
			let url = '<?= base_url() ?>';
			return url + path;
		}
	</script>

	<script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>

	<script src="<?= base_url('assets/js/func.js') ?>"></script>
	<script src="<?= base_url('assets/js/reporting/main.js') ?>"></script>
</body>

</html>