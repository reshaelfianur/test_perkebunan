<div>
	<table class="header">
		<thead>
			<tr>
				<th>
					<h1><?= $title ?></h1>
				</th>
			</tr>
		</thead>
	</table>
	<table class="body">
		<thead>
			<tr>
				<th>Tanggal Panen</th>

				<?php foreach ($kriteria as $key => $value) : ?>
					<th><?= $value->name ?></th>

					<?php
					$total[$value->id] = 0;
					?>
				<?php endforeach ?>
			</tr>

		</thead>
		<tbody>
			<?php foreach ($fetch as $key => $val) : ?>
				<tr>
					<td><?= $key ?></td>
					<?php foreach ($kriteria as $key2 => $val2) : ?>

						<?php
						$jumlah = (!empty($fetch[$key][$val2->id])) ? $fetch[$key][$val2->id]['jumlah'] : 0;
						// dd($fetch[$key][$val2->id]['jumlah']);
						$total[$val2->id] += $jumlah;

						?>

						<td><?= $jumlah ?></td>
					<?php endforeach ?>
				</tr>
			<?php endforeach ?>
			<tr class="footer">
				<td>Total</td>

				<?php foreach ($kriteria as $key => $value) : ?>
					<td><?= $total[$value->id] ?></td>
				<?php endforeach ?>
			</tr>
		</tbody>
	</table>
</div>