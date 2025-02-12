# Desafio técnico
***
#### Objetivo:
Desenvolver um módulo que simule um carrinho de compras básico, calculando o valor final de uma compra com base nos
itens adicionados e na forma de pagamento escolhida.

#### Descrição
Implemente um módulo que contenha a lógica para calcular o valor
total de uma compra com base nos itens adicionados ao carrinho e no método de pagamento.
Não é necessário implementar nenhuma integração com banco de dados, APIs, ou gateways de pagamento;

## Rode o projeto
#### Requisitos:
- docker
- bash terminal

#### Instruções:
- clone o repositório
- dentro da pasta raiz do projeto  rode o comando abaixo para tornar o run.sh um executável:
```bash
    chmod +x ./run.sh
 ```
- Instale as dependencias do projeto
```bash
  ./run.sh install
 ```
- configura os namespaces da aplicação seguindo a PSR-4
```bash
  ./run.sh composer dump-autoload
 ```
- Após a configuração do autoload basta rodar o projeto
```bash
  ./run.sh php index.php
 ```
- Para rodas os TESTES com PHPUnit
```bash
  ./vendor/bin/phpunit
 ```