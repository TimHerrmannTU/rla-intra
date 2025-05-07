<?php /* Template Name: page-testing */ ?>
<head>
	<style><?php include("static/scss/main.css"); ?></style>
</head>
<body>
	<!-- TEXT -->
	<div class="box">
		<h1>Headline 1</h1>
		<h2>Headline 2</h2>
		<h3>Headline 3</h3>
		<h4>Headline 4</h4>
		<h5>Headline 5</h5>
		<h6>Headline 6</h6>
		<p>Alles andere</p>
	</div>

	<!-- BUTTONS -->
	<div class="box col gap-1">
		<button>button</button>
		<button class="bordered">bordered</button>
		<button class="bordered active">active</button>
		<button class="bordered smooth">smooth</button>
		<div class="toggle-button">
			<div class="option left">links</div>
			<div class="slider"></div>
			<div class="option right">rechts</div>
		</div>
		<button class="bordered icon-only">
			<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 24 24"><path fill="currentColor" d="M9.5 3A6.5 6.5 0 0 1 16 9.5c0 1.61-.59 3.09-1.56 4.23l.27.27h.79l5 5l-1.5 1.5l-5-5v-.79l-.27-.27A6.52 6.52 0 0 1 9.5 16A6.5 6.5 0 0 1 3 9.5A6.5 6.5 0 0 1 9.5 3m0 2C7 5 5 7 5 9.5S7 14 9.5 14S14 12 14 9.5S12 5 9.5 5"/></svg>
		</button>
	</div>

	<!-- INPUTS -->
	<div class="box col gap-1">
		<div class="text-input-wrapper">
			<input type="text" placeholder="I'm a text input with a icon!">
			<div class="icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 24 24"><path fill="currentColor" d="M9.5 3A6.5 6.5 0 0 1 16 9.5c0 1.61-.59 3.09-1.56 4.23l.27.27h.79l5 5l-1.5 1.5l-5-5v-.79l-.27-.27A6.52 6.52 0 0 1 9.5 16A6.5 6.5 0 0 1 3 9.5A6.5 6.5 0 0 1 9.5 3m0 2C7 5 5 7 5 9.5S7 14 9.5 14S14 12 14 9.5S12 5 9.5 5"/></svg>
			</div>
		</div>
		<input type="checkbox" name="cb"><label for="cb">I'm the checkbox label!</label>
	</div>

</body>