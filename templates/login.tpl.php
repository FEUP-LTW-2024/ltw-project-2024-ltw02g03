<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/user.class.php');

function drawLoginForm(Session $session,$db) { ?>   
    <main>
        <section id="login">
            <h1>Login</h1>
            <form class="form-log" action="../actions/action_login.php" method="post"> 
                <label>
                    Email <input type="text" name="email">
                </label>
                <label>
                    Password <input type="password" name="password">
                </label>
                <div id="flex-login-regis">
                    <button type="submit">Login</button>
                    <a href="register.php">Register</a>  
                </div>
            </form>
        </section>
    </main>
<?php } ?>

<?php 
function drawRegisterForm(Session $session, $db) { ?>   
    <main>
        <section id="login">
            <h1>Register</h1>
            <form class="form-log">
                <h1>User</h1>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="name-group">
                    <div class="input-group">
                        <label for="first-name">First Name</label>
                        <input type="text" id="first-name" name="first-name" required>
                    </div>
                    <div class="input-group">
                        <label for="last-name">Last Name</label>
                        <input type="text" id="last-name" name="last-name" required>
                    </div>
                </div>
                <div class="location-group">
                    <h1>Location</h1>
                    <div class="input-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city">
                    </div>
                    <div class="input-group">
                        <label for="district">District</label>
                        <input type="text" id="district" name="district">
                    </div>
                    <div class="input-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country">
                    </div>
                    <div class="input-group">
                        <label for="postal-code">Postal Code</label>
                        <input type="text" id="postal-code" name="postal-code">
                    </div>
                    <div class="input-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone">
                    </div>
                </div>
                <div class="password-group">
                    <h1>Password</h1>
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="input-group">
                        <label for="repeat-password">Repeat Password</label>
                        <input type="password" id="repeat-password" name="repeat-password" required>
                    </div>
                </div>
                <div id="flex-login-regis">
                    <button type="submit" formaction="#" formmethod="post">Register</button>
                    <a href="login.php">Login</a>  
                </div>
            </form>
        </section>
    </main>
<?php } ?>


