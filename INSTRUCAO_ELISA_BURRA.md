### Passo a passo ABSURDAMENTE simples para rodar o VerdeZero (pq a elisa eh uma jumenta, e nao sabe o que ta fazendo num curso de TI)

#### 1) Instalar servidor (Apache + PHP + MySQL)

* No PowerShell, rode:

  ```powershell
  winget install --id ApacheFriends.Xampp.8.2 -e
  ```
* Abra o **XAMPP Control Panel**:

  ```powershell
  & "C:\xampp\xampp-control.exe"
  ```
* Clique em **Start** para **Apache** e **MySQL**.

#### 2) Colocar o projeto no lugar certo

* Extraia o `verdezero.zip` em:

  ```
  C:\xampp\htdocs\verdezero
  ```

#### 3) Criar o banco (se quiser login/ebooks reais)

* Abra no navegador: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
* Clique em **Importar**
* Escolha `C:\xampp\htdocs\verdezero\sql\schema.sql`
* Clique **Executar**

#### 4) Acessar o site

Abra no navegador:

```
http://localhost/verdezero/
```

#### 5) Usar

* **Home**: banners, atalhos para IMC e Água.
* **IMC**: calcule peso/altura → recebe classificação.
* **Água**: calcule litros/dia pelo peso.
* **Ebooks**: lista, busca e filtros Receitas/Exercícios.
* **Login**: crie conta e edite perfil (se MySQL ativo).

---

### Onde trocar coisas

* **Imagens** → `verdezero/assets/img/`
* **Cores e estilo** → `verdezero/assets/css/style.css`
* **Ebooks** → banco de dados (`ebooks`), ou em fallback em `includes/db.php`

---

👉 Resumindo: instale XAMPP, jogue a pasta em `htdocs`, importe o `.sql` (se quiser DB), abra `http://localhost/verdezero/` no navegador. Fim.
