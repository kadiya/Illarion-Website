<?php
	include $_SERVER['DOCUMENT_ROOT'].'/shared/shared.php';

	IllaUser::requireLogin();

	Page::Init();

	$pgSQL =& Database::getPostgreSQL( 'illarionserver' );
	$query = 'SELECT COUNT(*)'
	.PHP_EOL.' FROM chars'
	.PHP_EOL.' WHERE chr_accid = '.$pgSQL->Quote( IllaUser::$ID )
	;
	$pgSQL->setQuery( $query );
	$charcount = $pgSQL->loadResult();

	$servers = array();
	if ($charcount < IllaUser::$charlimit || IllaUser::$charlimit == 0)
	{
		$servers[] = 'rs';
	}

	if (IllaUser::auth('testserver'))
	{
		$servers[] = 'ts';
	}

	if (!count($servers))
	{
		exit('Fehler - Charakterlimit erreicht.');
	}

   Page::setTitle( array( 'Account', 'Neuen Charakter erstellen' ) );
   Page::setDescription( 'Auf dieser Seite kannst Du einen neuen Charakter für Illarion erstellen' );
   Page::setKeywords( array( 'Charaktere', 'Neu', 'erstellen' ) );

   Page::addJavaScript( array( 'prototype', 'effects'));
   Page::addJavaScript( 'newchar_1');

   Page::setXHTML();

?>

<h1>Neuen Charakter erstellen</h1>

<h2>Schritt 1</h2>

<p>Hier musst Du Name, Rasse und Geschlecht des Charakters festlegen.
Hilfreich kann dabei die <a href="<?php echo Page::getURL(); ?>/general/de_rpg_guide.php">RPG-Anleitung</a> sein.</p>

<form method="post" name="char_form" id="char_form" action="<?php echo Page::getURL(); ?>/community/account/de_newchar_1.php">
		<table style="width:100%;">
			<tbody>
				<tr>
					<td style="width:45%;vertical-align:top;" rowspan="2">
						Name:<br /><a href="<?php echo Page::getURL(); ?>/illarion/de_name_rules.php">Namensregeln</a> beachten
					</td>
					<td>
						<input type="text" name="charname" id="charname" value="" style="width:98%;" onkeyup="checkCharname();return true;" />
					</td>
				</tr>
				<tr>
					<td id="charname_result" style="height:70px;vertical-align:top;"></td>
				</tr>
				<tr>
					<td>Rasse:</td>
					<td>
						<select name="race" style="width:100%;">
							<?php foreach( IllaUser::$allowed_races as $race ): ?>
							<option value="<?php echo $race; ?>"><?php echo IllarionData::getRaceName($race); ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Geschlecht:</td>
					<td>
						<select name="sex" style="width:100%;">
							<option value="0">männlich</option>
							<option value="1">weiblich</option>
						</select>
					</td>
				</tr>
				<?php if (count($servers) == 2): ?>
				<tr>
					<td style="height:20px;" />
				</tr>
				<tr>
					<td>Server:</td>
					<td>
						<select name="server" id="server" style="width:100%;" onchange="checkCharname();return true;">
							<option value="0">Spielserver</option>
							<option value="1">Testserver</option>
						</select>
					</td>
				</tr>
				<tr>
					<td style="height:20px;" />
				</tr>
				<?php elseif ($servers[0] == 'ts' ): ?>
				<tr>
					<td style="height:20px;" />
				</tr>
				<tr>
					<td>
						Testserver
						<input type="hidden" name="server" value="1" />
					</td>
				</tr>
				<tr>
					<td style="height:20px;" />
				</tr>
				<?php else: ?>
				<tr>
					<td style="height:20px;"><input type="hidden" name="server" value="0" /></td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
	<p style="text-align:center;">
		<input type="hidden" name="action" value="newchar_1" />
		<input type="submit" name="submit" id="submit" value="Daten speichern" />
	</p>
	</form>