SHELL = /bin/bash

.PHONY: setup, reset, run, run-admin, publish, clear-cache

clear-cache:
	@echo "Clearing translation cache..."
	rm -rf var/cache/default/*
	@echo "Clearing queries cache..."
	rm -rf var/cache/gateway/*
	@echo "Clearing forms cache..."
	rm -rf var/cache/form/*
	@echo "Clearing locale cache..."
	rm -rf var/cache/locale/*

reset:
		@echo "Reset database..."
		php bin/doctrine-cli drop-db --force
		mysql -u root -p < application/configs/data/sql/database-dev.sql
		php bin/doctrine-cli create-tables
		mysql -u root -p < application/configs/data/sql/inserts.sql

run:
		php -S localhost:8000 -t ./edeco.mx

run-admin:
		php -S localhost:8000 -t ./admin.edeco.mx

publish:
		@echo "Deploy project"
		php bin/mage deploy to:production

setup:
		composer install
		bin/phing
		mysql -u $(RUSER) -p$(RPSWD) < application/configs/data/sql/database-dev.sql
		php bin/doctrine-cli create-tables
		mysql -u $(RUSER) -p$(RPSWD) < application/configs/data/sql/inserts.sql
		cp application/configs/data/images/gallery/* edeco.mx/images/gallery/
		cp application/configs/data/images/properties/* edeco.mx/images/properties/
		cp application/configs/data/images/thumbs/* edeco.mx/images/thumbs/
		@echo "Done!";