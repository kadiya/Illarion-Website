<?php
	include $_SERVER['DOCUMENT_ROOT'] . "/shared/shared.php";

	Page::setTitle( 'Start and stop the gameserver' );
	Page::setDescription( 'This page is used to start and stop the gameserver.' );
	Page::setKeywords( array( 'start', 'stop', 'gameserver' ) );

	Page::setXHTML();
	Page::Init();
?>

<h1>Control the gameserver</h1>

<?php if (file_exists('/home/vilarion/rs_restart.lock')): ?>
<p>The controls were locked by <b>Vilarion</b>.</p>
<?php exit; endif; ?> 

<?php if (file_exists('/home/martin/rs_restart.lock')): ?>
<p>The controls were locked by <b>martin</b>.</p>
<?php exit; endif; ?> 

<?php if (file_exists('/home/nitram/rs_restart.lock')): ?>
<p>The controls were locked by <b>Nitram</b>.</p>
<?php exit; endif; ?> 

<?php
	if (isset($_POST['mode'])) {
	    if ($_POST['mode'] == 'start') {
	        $process = popen('ulimit -c 30000; sudo -u illarionserver illarionserver /etc/illarion/illarionserver.cfg &', 'r');
	        if (!is_bool($process)) {
	            pclose($process);
	        }
	    } elseif ($_POST['mode'] == 'stop') {
	        `sudo -u illarionserver killall illarionserver -TERM`;
	    } elseif ($_POST['mode'] == 'kill') {
	        `sudo -u illarionserver killall illarionserver -KILL`;
	    }
	}
?>

<h2>Informations</h2>

<p>This page is used to start and stop the gameserver. Please ensture before starting the
gameserver that it is not running anymore.</p>

<h2>Checking state</h2>

<p>Testing if the gameserver is running:</p>
<?php
    $output = `ps -u illarionserver | grep "illarionserver"`;
    $running_server = false;
    if (strlen($output) > 10) {
        $running_server = true;
    } else {
        $running_server = false;
    }
?>
<?php if ($running_server): ?>
<p style="color: #495645;">Gameserver is running.</p>
<?php else: ?>
<p style="color: #be0000;">Gameserver is not running.</p>
<?php endif; ?>

<?php if ($running_server): ?>
<p>Testing if the gameserver is accepting connections:</p>
<?php
    $connection = @fsockopen('127.0.0.1', 3008, $errno, $errstr, 30);
    $working_server = false;
    if (is_bool($connection)) {
        $working_server = false;
    } else {
        $working_server = true;
        fclose($connection);
    }
?>
<?php if ($working_server): ?>
<p style="color: #495645;">Connection established successfully.</p>
<?php else: ?>
<p style="color: #be0000;">Failed to establish connection.</p>
<?php endif; ?>
<?php endif; ?>

<p>Results:</p>
<?php if ($running_server): ?>
<?php if ($working_server): ?>
<p>The server appears working normaly. To perform a restart the server has to be shut down
first and restarted after.</p>
<?php else: ?>
<p>The server is running but does not accept new connections. This means it is either crashed
or not fully started up. In case this state lasts longer then 5 minutes the server should be
killed and restarted.</p>
<?php endif; ?>
<?php else: ?>
<p>The server is not running and can be started now.</p>
<?php endif; ?>

<p><a href="<?php echo Page::getSecureURL(); ?>/restart/us_restart_rs.php">Refresh page</a></p>

<?php Page::insert_go_to_top_link(); ?>

<h2>Start the gameserver</h2>

<p>In this area you find the button to start the servers. This should only be done in case the
gameserver is not running.</p>

<?php if (isset($_POST['mode']) && $_POST['mode'] == 'start'): ?>

<p>Starting the server!</p>

<?php else: ?>

<form action="<?php echo Page::getSecureURL(); ?>/restart/us_restart_rs.php" method="post">
    <input type="submit" name="submit" value="Start gameserver"<?php echo ($running_server ? ' disabled="disabled" class="disabled"' : ''); ?> />
    <input type="hidden" name="mode" value="start" />
</form>

<?php endif; ?>

<?php Page::insert_go_to_top_link(); ?>

<h2>Shutdown the gameserver</h2>

<p>In this area you find the button to shutdown the gameserver. By doing so the players and the map will be saved before the
server exits. This method is the best to shutdown the server, how ever its possible that it does not work in case the server
is crashed.</p>

<?php if (isset($_POST['mode']) && $_POST['mode'] == 'stop'): ?>

<p>Shutting down the server!</p>

<?php else: ?>

<form action="<?php echo Page::getSecureURL(); ?>/restart/us_restart_rs.php" method="post">
    <input type="submit" name="submit" value="Shutdown gameserver"<?php echo ($running_server ? '' : ' disabled="disabled" class="disabled"'); ?> />
    <input type="hidden" name="mode" value="stop" />
</form>

<?php endif; ?>

<?php Page::insert_go_to_top_link(); ?>

<h2>Kill the gameserver</h2>

<p>In this area you find the button to kill the gameserver. This results in the gameserver exiting without saving the players or the
map. This method will work in any case but does the loss of data it should only be used in case the normal shutdown fails. In the most
cases the status diagnostic will show if you are able to use the shutdown or if you have to kill the server.</p>

<?php if (isset($_POST['mode']) && $_POST['mode'] == 'stop'): ?>

<p>Killing the server.</p>

<?php else: ?>

<form action="<?php echo Page::getSecureURL(); ?>/restart/us_restart_rs.php" method="post">
    <input type="submit" name="submit" value="Kill gameserver"<?php echo ($running_server ? '' : ' disabled="disabled" class="disabled"'); ?> />
    <input type="hidden" name="mode" value="kill" />
</form>

<?php endif; ?>

<?php Page::insert_go_to_top_link(); ?>