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
            <form>
                <label>
                    Email <input type="text" name="username">
                </label>
                <label>
                    Password <input type="password" name="password">
                </label>
                <div id="flex-login-regis">
                    <button formaction="#" formmethod="post">Login</button>
                    <a href="register.php">Register</a>  
                </div>
            </form>
        </section>
    </main>
<?php } ?>


<?php function drawRegisterForm(Session $session,$db) { ?>   
    <main>
          <section id="login">
            <h1>Register</h1>
            <form>
              <label>
                Email <input type="text" name="username">
              </label>
              <label>
                Password <input type="password" name="password">
              </label>
              <label>
                Repeat Password <input type="password" name="password">
              </label>
              <div id="flex-login-regis">
                <button formaction="#" formmethod="post">Register</button>
                <a href="login.php">Login</a>  
              </div>
            </form>
          </section>
        </main>
<?php } ?>

