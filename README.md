<h1>YouTube Searcher</h1>
<p>YouTube Searcher is an application written on PHP that uses MySQL database. It can help you to find videos using YouTube API, cache all the results in database, manage favorite videos, watch them and create accounts.</p>
<h2>How to use?</h2>
<ul>
    <li>To <b>search videos</b> go to the main page, type your query and press button to search</li>
    <li>If you are registered press a button <b>'Log in'</b> to log in your account</li>
    <li>If you are not registered press a button <b>'Register'</b> to create a new account</li>
    <li><b>Only registered users</b> are able to add/remove videos from favorite list</li>
    <li>To <b>add/remove video from favorites</b> just click on 'like' button</li>
    <li>To browse all favorite videos press a button <b>'Favorite videos'</b></li>
    <li>By clicking on the video the modal form will appear where you can <b>watch video</b></li>
</ul>
<h2>How to install?</h2>
<ol>
  <li>Upload the contents of the folder "web" on your web server that supports PHP.</li>
  <li>Create a new MySQL database.</li>
  <li>Import a file "import.sql" from the folder "db" to your database.</li>  
  <li>Navigate to "config" directory on your web server.</li>
  <li>Edit file "config.php": set API key.</li>
  <li>Edit file "database.php": set database host, name, user, password.</li>
</ol>
<h2>Demo online</h2>
<p>Try YouTube Searcher <a href="http://lightit.r-launcher.su/" target="_blank">here</a>.</p>