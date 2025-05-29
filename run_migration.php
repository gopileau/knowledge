<?php
require 'config/config.php';
require 'database/migrations/003_create_course_relations.php';

// Execute the migration
$migration = new CreateCourseRelations();
$migration->up();
echo "Migration executed successfully.";
