.DEFAULT_GOAL := help

help: ## Afficher cette aide
	echo -e "\033[33m-- HELP --\033[0m"
	echo -e "\033[33mGuide des commandes :\033[0m"
	echo ""
	grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[35m%-15s\033[0m %s\n", $$1, $$2}'
	echo ""

## -- Initialiser ----------------------------------------------------------
init: ## Initialiser le projet Symfony
	if [ -f app/.env ]; then \
		echo -n -e "\033[36mUn .env existe deja. Le remplacer ? (y/n) : \033[0m"; read rep; \
		if [ "$$rep" != "y" ]; then \
			echo -e "\033[31mAnnule.\033[0m"; exit 0; \
		else \
			echo -e "\033[33m1) Remplacer .env par .env.dev\033[0m"; \
			echo -e "\033[33m2) Remplacer .env par .env.prod\033[0m"; \
			echo -e "\033[33m3) Annuler\033[0m"; \
			echo -n -e "\033[32mChoix : \033[0m"; read choix; \
			if [ "$$choix" = "1" ]; then \
				cp app/.env.dev app/.env; \
				echo -e "\033[32m Le fichier .env a bien ete remplace par .env.dev\033[0m"; \
			elif [ "$$choix" = "2" ]; then \
				cp app/.env.prod app/.env; \
				echo -e "\033[32m Le fichier .env a bien ete remplace par .env.prod\033[0m"; \
			else \
				echo -e "\033[31mAnnule.\033[0m"; exit 0; \
			fi; \
		fi; \
	else \
		echo -e "\033[34m Le .env n'existe pas, quel .env voulez vous utiliser ?\033[0m"; \
		echo -e "\033[33m1) Creer .env via .env.dev\033[0m"; \
		echo -e "\033[33m2) Creer .env via .env.prod\033[0m"; \
		echo -e "\033[33m3) Annuler\033[0m"; \
		echo -n -e "\033[32mChoix : \033[0m"; read choix; \
		if [ "$$choix" = "1" ]; then \
			cp app/.env.dev app/.env; \
			echo -e "\033[32m Le fichier .env a bien ete remplace par .env.dev\033[0m"; \
		elif [ "$$choix" = "2" ]; then \
			cp app/.env.prod app/.env; \
			echo -e "\033[32m Le fichier .env a bien ete remplace par .env.prod\033[0m"; \
		else \
			echo -e "\033[31mAnnule.\033[0m"; exit 0; \
		fi; \
	fi; \

## -- Start ----------------------------------------------------------
start: ## Lancer les conteneurs Docker
	docker compose up -d

## -- Stop ----------------------------------------------------------
stop: ## Arrêter les conteneurs Docker
	docker compose down

## -- Git Bundle ----------------------------------------------------------
bundle: ## Cree un bundle
	git bundle create projet-stage.bundle HEAD
	echo -e "\033[32mFichier 'projet-stage.bundle' cree avec succes.\033[0m"

## -- Install après Clone ----------------------------------------------------------
install: ## Installer les dépendances et configurer la base de données après un clone
	cp -n app/.env app/.env.local || true
	docker compose up -d --build
	sleep 10
	docker compose exec -T app composer install --no-interaction
	docker compose exec -T app php bin/console doctrine:database:create --if-not-exists
	docker compose exec -T app php bin/console doctrine:migrations:migrate --no-interaction