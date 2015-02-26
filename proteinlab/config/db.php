<?php

define( 'REDBEAN_MODEL_PREFIX', '\\Application\Models\\' ); 

\R::setup('mysql:host=localhost;dbname=%_DBNAME_%','%_DBUSER_%','%_DBPASSWORD_');
\R::freeze(true);

