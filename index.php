<?php
require_once('plugboard.php');
require_once('rotors.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Enigma Machine</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<h1>Enigma Machine</h1>
		<form method="POST">
			<h3><a href="#" title="Show/hide" class="collapse_button rotors" onclick="document.getElementById('rotors').classList.toggle('closed'); this.classList.toggle('closed'); sessionStorage.setItem('rotors_closed', this.classList.contains('closed')); redraw(); return false;"></a> Rotor positions</h3>
			<div id="rotors">
				<span class="options"><a href="#" onclick="randomRotors(); return false;">Random</a> | <a href="#" onclick="resetRotors(); return false;">Reset</a></span>
				<div>
					<label for="pos1">Rotor 1</label>
					<select name="pos1" id="pos1">
						<?php for ($i = 0; $i < count($rotor1); $i++) { ?>
						<option value="<?php echo $i; ?>" <?php if (!empty($_POST['pos1']) && $_POST['pos1'] == $i) { echo 'selected'; } ?>><?php echo $i; ?></option>
						<?php } ?>
					</select>
				</div>
				<div>
					<label for="pos2">Rotor 2</label>
					<select name="pos2" id="pos2">
						<?php for ($i = 0; $i < count($rotor2); $i++) { ?>
						<option value="<?php echo $i; ?>" <?php if (!empty($_POST['pos2']) && $_POST['pos2'] == $i) { echo 'selected'; } ?>><?php echo $i; ?></option>
						<?php } ?>
					</select>
				</div>
				<div>
					<label for="pos3">Rotor 3</label>
					<select name="pos3" id="pos3">
						<?php for ($i = 0; $i < count($rotor3); $i++) { ?>
						<option value="<?php echo $i; ?>" <?php if (!empty($_POST['pos3']) && $_POST['pos3'] == $i) { echo 'selected'; } ?>><?php echo $i; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			
			<h3>Input</h3>
			<div id="input_area">
				<textarea id="input" name="input" spellcheck="false" autocapitalize="off" autocorrect="off"><?php
				if (!empty($_POST['input'])) {
					echo $_POST['input'];
				}
				?></textarea>
			</div>
			
			<h3><a href="#" title="Show/hide" class="collapse_button plugboard" onclick="document.getElementById('plugboard_options').classList.toggle('closed'); this.classList.toggle('closed'); sessionStorage.setItem('plugboard_closed', this.classList.contains('closed')); redraw(); return false;"></a> Plugboard <span class="instructions">(Drag to create wires between letters)</span></h3>
			<div id="plugboard_options"><a href="#" onclick="randomPlugs(); return false;">Random</a> | <a href="#" onclick="resetPlugs(); return false;">Reset</a></div>
			<div id="plugboard">
				<?php
				$i = 0;
				foreach ($letters as $l) { ?>
				<div id="<?php echo $l; ?>" data-index="<?php echo $i; ?>"><?php echo $l; ?></div>
				<?php
				$i++;
				} ?>
			</div>
			
			<input type="hidden" id="plugboard_config" name="plugboard" <?php if (!empty($_POST['plugboard'])) { echo 'value="'.$_POST['plugboard'].'"'; } ?> />
			
			<div style="text-align:right">
				<button type="submit" onclick="submitForm(); return false;">Go</button>
			</div>
		</form>
		<h3>Output</h3>
		<div id="output_area">
			<textarea id="output" spellcheck="false" autocapitalize="off" autocorrect="off" onfocus="this.select();"><?php
				if (!empty($_POST['input'])) {
					$input = $_POST['input'];
					if (!empty($_POST['pos1']))
						$rotor1_pos = $_POST['pos1'];
					if (!empty($_POST['pos2']))
						$rotor2_pos = $_POST['pos2'];
					if (!empty($_POST['pos3']))
						$rotor3_pos = $_POST['pos3'];
					if (!empty($_POST['plugboard']) && $_POST['plugboard'] != '')
						$plugboard = json_decode($_POST['plugboard']);
					include('process.php');
					echo $output;
				}
			?></textarea>
		</div>
		<footer>&copy; Ben Grant <?php echo date('Y'); ?>. <a href="https://github.com/jsplumb/jsplumb">jsPlumb Community Edition</a>.</footer>
		<script src="jsplumb.min.js"></script>
		<script src="script.js"></script>
	</body>
</html>