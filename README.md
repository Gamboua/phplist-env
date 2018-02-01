# Ambiente dev

### **Instalação do Docker**

Instale as dependências:
```bash
sudo apt-get install apt-transport-https ca-certificates curl software-properties-common
```

Adicione a chave do repositório:
```bash
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
```

Adicione o endereço do repositório:
```bash
sudo add-apt-repository \
   "deb [arch=amd64] https://download.docker.com/linux/ubuntu \
   $(lsb_release -cs) \
   stable"
```

Atualize e instale o docker-ce:
```bash
apt update && apt install -y docker-ce 
```

### **Instalação do Docker compose**

Baixe o binário
```bash
sudo curl -L https://github.com/docker/compose/releases/download/1.16.1/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose
```

Dê permissão de execução:
```bash
sudo chmod +x /usr/local/bin/docker-compose
```

### **Configuração**

Adicione a linha no arquivo **/etc/hosts**:

```bash
172.25.0.101    phplist.local
```

### **Subindo ambiente**

Gerando a imagem PHPList:
```bash
cd phplist-caixa
sudo docker build -t phplist-caixa -f build/Dockerfile .
```

Rode o composer no diretório:
```bash
composer install
```
> **NOTA**: Garanta sempre que o **vendor/** esteja criado no projeto clonado.

Para iniciar o ambiente:
```bash
sudo docker-compose up -d --build
```

Veja se os containers estão **Up**:

```bash
sudo docker-compose ps
```

Estes containers devem estar inicializados:
- phplist
- mysql
- mailhog
- postgresql
- cache
- migration (deve estar como EXITED)

### **Migration**
O comando **vendor/bin/phinx migrate -e local** é executado sempre que o comando **docker-compose up** for usado. Para verificar a saída de log da migration:
```bash
cat migration.log
```

Link: http://phplist.local  
Caixa de saída MailHog: http://localhost:8025

### **MySQL**

O MySQL está exposto na porta **33306** para acessar basta usar **localhost:33306**

### **POSTGRESQL**
O container do Postgres, ao iniciar, escuta os scripts dentro de **/docker-entrypoint-initdb.d**. Os dados do container estão compartilhados por um volume, por isso, caso queira atualizar o dump, será necessário antes remover o volume.

Interrompa os containers, caso esteja em execução:
```bash
docker-compose down
```
Liste os volumes e exclua o volume com nome ***phplistcaixa_data_postgres_db***
```bash
docker volume rm phplistcaixa_data_postgres_db
```
É possível substituir o arquivo com o dump ou adicionar um novo. Basta inserir uma entrada no arquivo ***build/Dockerfile-postgresql*** e no arquivo ***dev-postgresql-dump.sh***:
```bash
COPY build/nome_do_dump /docker-entrypoint-initdb.d/
```
```bash
pg_restore -U phplist -h localhost -d phplist /docker-entrypoint-initdb.d/nome_do_dump
```
Ao subir novamente o docker-compose, o restore será feito automaticamente.

> **NOTA**: O nome do volume pode variar, entretanto o prefixo ***phplistcaixa_*** será sempre o mesmo. Verifique as opções antes de remover.


### **Comando Docker úteis**

Listar imagens
```bash
docker image ls
```

Listar containers ativos
```bash
docker container ls
```

Listar todos os containers
```bash
docker container ls -a
```

Remover containers do docker-compose
```bash
docker-compose down
```

Remover container do docker-compose e tudo que foi gerado por ele
```bash
docker-compose down -v
```

Mostrar informações do container
```bash
docker container inspect id_ou_nome_do_container
```

Acessar container
```bash
docker exec -it id_ou_nome_do_container /bin/bash