<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Gimme a Song</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      <form id="wordForm">
        <div class="form-group">
          <label for="wordField">Enter a word</label>
          <input type="text" class="form-control" id="wordField">
        </div>

        <label for="dropdownLanguage">Language</label>
        <div class="dropdown">
          <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownLanguage" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            English
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownLanguage">
            <a class="dropdown-item" href="#" data-lang-id="en">English</a>
            <a class="dropdown-item" href="#" data-lang-id="fr">French</a>
            <a class="dropdown-item" href="#" data-lang-id="it">Italian</a>
            <a class="dropdown-item" href="#" data-lang-id="zh">Chinese</a>
            <a class="dropdown-item" href="#" data-lang-id="hi">Hindi</a>
            <a class="dropdown-item" href="#" data-lang-id="ru">Russian</a>
            <a class="dropdown-item" href="#" data-lang-id="es">Spanish</a>
            <a class="dropdown-item" href="#" data-lang-id="ko">Korean</a>
            <a class="dropdown-item" href="#" data-lang-id="ar">Arabic</a>
            <a class="dropdown-item" href="#" data-lang-id="de">German</a>
            <a class="dropdown-item" href="#" data-lang-id="pt">Portuguese</a>
            <a class="dropdown-item" href="#" data-lang-id="ja">Japanese</a>
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>

    <script
    	src="https://code.jquery.com/jquery-3.3.1.min.js"
    	integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    	crossorigin="anonymous">
  	</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
      $(document).ready(function() {
        let langSelected = 'en';

        $('.dropdown-item').click(function() {
          $('#dropdownLanguage').html($(this).html());
          langSelected = $(this).data('langId');
        });

        $('#wordForm').on('submit', function(event) {
          event.preventDefault();
          let wordIn = $('#wordField').val().trim();
          let langIn = langSelected
          console.log('Sending request to backend for word ' + wordIn + ' in lang ' + langIn);

          $.ajax({
            method: 'POST',
            url: 'apiConnect.php',
            data: {
              action: 'wordQuery',
              word: wordIn,
              lang: langIn
            }
          }).done(function(results) {
            console.log(results);
            console.log(results.length);
            <?php
            for($)
             ?>
          }).fail(function(results) {
            console.log("AJAX error sending information to backend.");
          });

          return false;
        });
      });
    </script>
  </body>
</html>
