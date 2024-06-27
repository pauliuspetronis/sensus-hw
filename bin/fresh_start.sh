#!/usr/bin/env sh
set -ex
composer install --prefer-dist --no-progress --no-interaction --no-scripts;

until bin/console doctrine:query:sql "select 1" >/dev/null 2>&1; do
    (>&2 echo "Waiting for MySQL to be ready...")
    sleep 1
done

bin/console doctrine:database:drop --force;
bin/console doctrine:database:create;
bin/console doctrine:schema:create;
bin/console doctrine:migrations:sync-metadata-storage;
bin/console doctrine:migrations:version --add --all -n;
bin/console doctrine:fixtures:load --no-interaction --append;

composer auto-scripts --no-interaction;
