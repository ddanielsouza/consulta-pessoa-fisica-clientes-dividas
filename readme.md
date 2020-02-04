# https://github.com/ddanielsouza/consulta-pessoa-fisica-clientes-dividas

Serviço desenvolvido no micro <a href="https://lumen.laravel.com/">framework Lumen</a> versão 6.0

### Proposta para o desenvolvimento deste projeto ###
Fazer um serviço para consumir dados bastantes sensiveis e os armazenarem com segurança

### Solução ###
A solução adotada foi o uso de <a href="https://laravel.com/docs/master/eloquent-mutators">mutators do eloquent</a> para criptografar os dados ao salvarem ao banco e descriptografar ao serem consultados pelo serviço, e para a consulta desse dados no banco foi adotas colunas com hash MD5, o MD5 é geralmente um via de mão única não sendo descriptografado, mas que com a mesma chave, valores iguais geram as mesmas hash assim permitindo a busca desses campos no banco em uma coluna armazena com esses hashs e outra coluna que pode ser descriptografada

```PHP
    <?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Crypt;

    class Client extends Model
    {
        use \App\Utils\Helpers\ISOSerialization;

        protected $table = "clients";

        protected $fillable = [
            'id',
            'registry',
            'name',
            'hash_registry'
        ];

        protected $hidden = ['registry', 'hash_registry'];
        protected $appends = ['dctRegistry'];

        public function getDctRegistryAttribute()
        {
            return empty($this->registry) ? null : decrypt($this->registry);
        }

        public function setDctRegistryAttribute($value)
        {
            $this->registry = encrypt($value);
            //HASH UNICA PARA EVITAR DUPLICAR OS CPFs E OS CONSULTAR NO BANCO
            $this->hash_registry = hash('md5', $value);
        }

        // ... 
    }
```

### Pré-Requisitos
* Docker (Aqui demonstrarei como executar o projeto apenas no docker)
* git

### OBS
Este tem dependencia do projeto consulta-pessoa-fisica-utils para maiores detalhes acesse <a href="https://github.com/ddanielsouza/consulta-pessoa-fisica-utils">https://github.com/ddanielsouza/consulta-pessoa-fisica-utils</a>

### Executando o projeto

1. Configurando o Banco
    * ```docker run -itd -p 5432:5432 -e POSTGRES_PASSWORD=123456 --name pgsql postgres```
    * Será nescessário a criação da database "consulta-pessoa-fisica-clientes-dividas" então  para facilitar, rode a imagem do SGBD pgadmin 4 <br>
     ``` docker run -itd -p 5050:80 -e PGADMIN_DEFAULT_EMAIL=exemplo@email.com -e PGADMIN_DEFAULT_PASSWORD=123456 --name pgsql postgres --link pgsql ```
    * A aplicação em php irá rodar as "migrations" então não se preocupe em rodar nenhum script sql, apenas crie a database com nome de "consulta-pessoa-fisica-clientes-dividas"
2. Instalando
    * ``` git clone git@github.com:ddanielsouza/consulta-pessoa-fisica-clientes-dividas.git ```
    * ``` git submodule update --init --recursive ```
    * ``` docker build -t dividas . ```
3. Rodando
    * ``` docker run -itd -p 8001:80 --link pgsql --name dividas --link auth dividas ```
    
Após executar o banco será populados com alguns dados aleatorios, código: https://github.com/ddanielsouza/consulta-pessoa-fisica-clientes-dividas/blob/master/database/migrations/2020_02_02_030425_payloads.php
    
### Arquitetura dos micros servicos
A arquitetura adotada para os microsserviços foi a de login unico (Single sign-on)
<img src="https://i.pinimg.com/originals/72/2d/dc/722ddc85dad8a4cdf783dbc23e660d33.png"/>

* AUTH: <a href="https://github.com/ddanielsouza/consulta-pessoa-fisica-auth">https://github.com/ddanielsouza/consulta-pessoa-fisica-auth</a> 
* consulta-pessoa-fisica-clientes-dividas: <a href="https://github.com/ddanielsouza/consulta-pessoa-fisica-clientes-dividas">https://github.com/ddanielsouza/consulta-pessoa-fisica-clientes-dividas</a> (<b>Este Projeto</b>)
* consulta-pessoa-fisica-credito-pessoal: <a href="https://github.com/ddanielsouza/consulta-pessoa-fisica-credito-pessoal">https://github.com/ddanielsouza/consulta-pessoa-fisica-credito-pessoal</a>
* consulta-pessoa-fisica-eventos: <a href="https://github.com/ddanielsouza/consulta-pessoa-fisica-eventos">https://github.com/ddanielsouza/consulta-pessoa-fisica-eventos</a>
* consulta-pessoa-fisica-utils: <a href="https://github.com/ddanielsouza/consulta-pessoa-fisica-utils">https://github.com/ddanielsouza/consulta-pessoa-fisica-utils</a>
