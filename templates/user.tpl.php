<?php declare(strict_types = 1); ?>

<?php function drawProfileForm(User $user) { ?>
<h2>Profile</h2>
<form action="../actions/action_edit_profile.php" method="post" class="profile">

  <label for="first_name">First Name:</label>
  <input id="first_name" type="text" name="first_name" value="<?=$user->firstName?>">
  
  <label for="last_name">Last Name:</label>
  <input id="last_name" type="text" name="last_name" value="<?=$user->lastName?>">  
  
  <button type="submit">Save</button>
</form>
<?php } ?>