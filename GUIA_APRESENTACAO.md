# Guia rapido - Praticando Aula 16b

## Como executar pelo GitHub

1. Crie uma EC2 Ubuntu com as portas `22` e `80` liberadas.
2. No repositorio GitHub, configure os secrets:
   - `EC2_HOST`: IP ou DNS da EC2.
   - `EC2_SSH_KEY`: chave privada SSH da EC2.
3. Envie o projeto para a branch `main`.

O GitHub Actions instala tudo, cria o banco, configura a aplicacao e publica automaticamente.

Acesse: `http://IP_DA_EC2`

phpMyAdmin:

- URL: `http://IP_DA_EC2/phpmyadmin`
- Usuario: `laraveluser`
- Senha: `senha_da_nasa`

## URLs principais

| URL | Funcao |
|---|---|
| `/` | Pagina inicial e busca |
| `/register` | Cadastro |
| `/login` | Login |
| `/dashboard` | Meus eventos |
| `/events/create` | Criar evento |
| `/events/{id}` | Detalhes do evento |
| `/events/edit/{id}` | Editar evento |

## O que apresentar

Use dois usuarios: **Usuario A**, dono do evento, e **Usuario B**, participante.

1. Cadastre e entre com o Usuario A.
2. Crie um evento e mostre o nome do dono nos detalhes.
3. Na pagina inicial, busque o evento pelo titulo ou cidade.
4. Na dashboard, edite o evento e mostre a mensagem de sucesso.
5. Entre com o Usuario B em outro navegador.
6. Confirme presenca e mostre a contagem de participantes.
7. Mostre o evento na dashboard do Usuario B.
8. Como Usuario B, tente abrir `/events/edit/{id}` e mostre o bloqueio.
9. Saia do evento e mostre que a contagem diminuiu.
10. Como Usuario A, exclua o evento e mostre a confirmacao antes de apagar.

Opcionalmente, mostre:

- `events.user_id`: identifica o dono do evento.
- `event_user`: registra os participantes.

## Teste rapido

```bash
php artisan test
php artisan route:list
```
