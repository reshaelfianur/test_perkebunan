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
				<th>Divisi</th>
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
			<?php
			$divisiExist = [];
			?>
			<?php foreach ($fetch as $key => $val) : ?>
				<?php
				$countDivisi = count($fetch[$key]);
				$divisi = explode('_', $key)[0];
				$tanggal = explode('_', $key)[1];
				?>

				<tr>
					<?php if (!in_array($divisi, $divisiExist)) : ?>
						<td><?= $divisi ?></td>
						<?php $divisiExist = [$divisi]; ?>
					<?php else : ?>
						<td></td>
					<?php endif ?>
					<td><?= $tanggal ?></td>

					<?php foreach ($kriteria as $key2 => $val2) : ?>
						<?php
						$jumlah = (!empty($fetch[$key][$val2->id])) ? $fetch[$key][$val2->id]['jumlah'] : 0;
						$total[$val2->id] += $jumlah;
						?>

						<td><?= $jumlah ?></td>
					<?php endforeach ?>
				</tr>
			<?php endforeach ?>
			<tr class="footer">
				<td colspan="2">Total</td>

				<?php foreach ($kriteria as $key => $value) : ?>
					<td><?= $total[$value->id] ?></td>
				<?php endforeach ?>
			</tr>
		</tbody>
	</table>
</div>