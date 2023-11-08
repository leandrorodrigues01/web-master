# Desenvolvimento Web II - Prova 1 🤓

<br>

<p align="center">
  <a href="#-about-the-project">Sobre o Projeto</a> •
  <a href="#-technologies">Tecnologias</a> •
  <a href="#-getting-started">Executando o projeto</a> •
  <a href="#-license">Licença</a>
</p>

<p align="center">
 
 
<img alt="project preview" src="./public/site-preview.png" width="75%"   >  
</p>

## 👩‍💻 Sobre o Projeto

Este é um projeto full stack que oferece operações de criação, leitura, atualização e exclusão (CRUD) para produtos e usuários, tudo isso com uma interface atraente e moderna. O backend é desenvolvido em PHP, enquanto o frontend utiliza tecnologias de ponta, incluindo Next.js 13, Tailwind CSS, Zod e outras ferramentas atualizadas do mercado.

Foi implementado um pequeno atraso de 1 a 2 segundos nas chamadas à API para permitir a exibição dos indicadores de carregamento na tela.

## 🚀 Tecnologias

- [NextJs 13](https://nextjs.org/)
- [PHP](https://www.php.net/)
- [Radix UI](https://www.radix-ui.com/)
- [React Hook Form](https://react-hook-form.com/)
- [React-Toastify](https://fkhadra.github.io/react-toastify/introduction)
- [Rive Animations](https://rive.app/)
- [Tailwind Css](https://tailwindcss.com/)
- [Typescript](https://www.typescriptlang.org/)
- [Zod](https://github.com/colinhacks/zod)

## 💻 Executando o projeto

Para rodar a aplicação, você vai precisar executar o servidor PHP e o aplicativo web.

### Requisitos

- [PHP](https://www.php.net/).
- [Node.js](https://nodejs.org/en/)
- [Yarn](https://classic.yarnpkg.com/) ou [NPM](https://www.npmjs.com/) _(exemplos são com NPM)_
- [Docker](https://www.docker.com/) (**Opcional**, caso não tenha PHP)

**Mova o projeto para a area de trabalho(Desktop) e acesse raiz do projeto em seu terminal:**

```bash
$ cd provaweb-master
```

**Para executar o aplicativo back-end, você precisa seguir as etapas abaixo**

Antes de iniciar o servidor do aplicativo back-end, é fundamental verificar se o caminho para o banco de dados está configurado corretamente. Caso esteja executando o servidor a partir de um diretório diferente de "/app", será necessário ajustar a linha 57 do arquivo Model.php localizado em /backend/Model/Model.php. Para isso, remova a parte "/app/backend/" da string databasePath, que inicialmente se apresenta da seguinte forma: `$databasePath = '/app/backend/sqlite/test_drive.db';`,

Após isso é só iniciar o servidor:

```bash
# Iniciar o servidor
$  php -S localhost:8080 -t provaweb-master

```

**Para executar o front-end, você precisa seguir as etapas abaixo**

```bash
# Acessar a pasta do front-end
$ cd frontend

# Instale as dependências do projeto
$ npm i

# Iniciar o aplicativo
$ npm run dev

# Seu frontend estará disponível na URL http://localhost:3000/
```

**OBSERVAÇÃO:** No diretório /frontend, você encontrará o arquivo `.env` Dentro deste arquivo, a URL do servidor está configurada como `http://localhost:8080`. Se o seu servidor estiver em outra URL, será necessário fazer a devida alteração neste arquivo.

### Etapas opcionais

**Para executar o backend no docker (caso não tenha PHP instalado), siga o passo a passo abaixo**

Abra um terminal e navegue até o diretório onde está o Dockerfile em: /provaweb-master

```bash
# Cria uma imagem Docker chamada php-server usando as instruções no Dockerfile.
$ docker build -t php-server .

# Executa um contêiner a partir da imagem php-server, mapeando a porta 80 dentro do contêiner para a porta 8080 em sua máquina local.
$ docker run -d -p 8080:80 --name php-server-container -v $(pwd):/app php-server
```

Você pode acessar o servidor PHP em http://localhost:8080. Se tudo estiver configurado corretamente

**Comandos para ambiente de desenvolvimento UNIX**

## 📝 Licença

Este projeto está licenciado sob a licença MIT - consulte o arquivo [LICENSE](LICENSE) para obter detalhes.

---
