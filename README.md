# Praticando 16b - HDC Events

Projeto Laravel 10 do praticando da Aula 16b, com Jetstream Livewire, dashboard de eventos, dono do evento, CRUD protegido e participacao em eventos.

## Deploy automatico pelo GitHub Actions

O workflow `.github/workflows/deploy-ec2.yml` executa os testes e configura uma EC2 Ubuntu automaticamente.

No primeiro deploy, ele:

- instala Apache, PHP, Composer e MySQL;
- instala e configura o phpMyAdmin;
- cria o banco e o usuario MySQL `laraveluser`;
- cria o `.env` e a `APP_KEY`;
- executa migrations, build, caches e configura o Apache;
- verifica se a aplicacao esta respondendo.

Configure somente estes GitHub Secrets:

- `EC2_HOST`: IP publico ou DNS da EC2.
- `EC2_SSH_KEY`: chave privada SSH que acessa a EC2.

Variaveis opcionais:

| Variavel | Padrao |
|---|---|
| `EC2_USER` | `ubuntu` |
| `APP_URL` | `http://EC2_HOST` |
| `EC2_DEPLOY_PATH` | `/var/www/html/praticando16b` |
| `DB_DATABASE` | `hdcevents` |
| `APP_NAME` | `HDC_Events` |

Prepare uma EC2 Ubuntu com as portas `22` e `80` liberadas. Para gerar a chave de deploy:

```bash
ssh-keygen -t ed25519 -C "github-actions-praticando16b" -f ~/.ssh/praticando16b_actions
```

Adicione a chave publica ao `~/.ssh/authorized_keys` da EC2 e coloque a chave privada no secret `EC2_SSH_KEY`.

Depois, envie para a branch `main` ou execute manualmente em **Actions > Deploy Laravel to EC2 > Run workflow**.

## phpMyAdmin

- URL: `http://EC2_HOST/phpmyadmin`
- Usuario: `laraveluser`
- Senha: `senha_da_nasa`

Essas credenciais sao fixas para facilitar a apresentacao da atividade. Nao use essa senha em um ambiente real.

## Local

```bash
composer install
npm install
npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```
