<!DOCTYPE html>
<html>

<head>
  <title>PHP SMS</title>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css" />
</head>

<body>

  <h1>PHP SMS</h1>
  <form method="post" action="send.php">
    <label for="number">Number</label>
    <input type="text" name="number" id="number" />
    <label for="message">Message</label>
    <textarea name="message" id="message"></textarea>
    <fieldset>
      <legend>Provider</legend>
      <label>
        <input type="radio" name="provider" value="infobip" checked /> Infobip
      </label>
      <br />
      <label>
        <input type="radio" name="provider" value="twilio" /> Twilio
      </label>
    </fieldset>
    <button>Send</button>
  </form>
</body>

</html>