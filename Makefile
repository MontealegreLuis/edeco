SHELL = /bin/bash

.PHONY: setup

reset: @echo "Reset database..."
		php bin/doctrine-cli drop-db
		mysql -u root -p < application/configs/data/sql/database-dev.sql
		mysql -u root -p < application/configs/data/sql/schema.sql
		mysql -u root -p < application/configs/data/sql/inserts.sql

run:
		php -S localhost:8000 -t ./edeco.mx

run-admin:
		php -S localhost:8000 -t ./admin.edeco.mx

setup:
		composer install
		mysql -u root -p < application/configs/data/sql/database-dev.sql
		mysql -u root -p < application/configs/data/sql/schema.sql
		mysql -u root -p < application/configs/data/sql/inserts.sql
		cp application/configs/data/images/gallery/* edeco.mx/images/gallery/
		cp application/configs/data/images/properties/* edeco.mx/images/properties/
		cp application/configs/data/images/thumbs/* edeco.mx/images/thumbs/
		@echo "Done!";