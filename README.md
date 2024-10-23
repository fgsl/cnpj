# Validação do CNPJ alfanumérico em PHP

A [Instrução Normativa nº 2.229](http://normas.receita.fazenda.gov.br/sijut2consulta/link.action?idAto=141102) da Receita Federal do Brasil altera, a partir de julho de 2026, o formato do CNPJ para incluir uma combinação de letras e números.

Este componente provê classes para validar o CNPJ de acordo com esse novo formato.

O método `JValidator->calculaDV()` obtém o dígito verificador de uma base CNPJ.

O método `JValidator->isValid()` retorna verdadeiro se o CNPJ (completo com dígito verificador) for válido.

A classe [JValidatorTest](./tests/Fgsl/CNPJ/JValidatorTest.php) ilustra o funcionamento da classe JValidator e você pode executá-la com PHPUnit para comprovar o funcionamento.

```php
class JValidatorTest extends TestCase
{
    public function testCNPJSemFormatacao()
    {
        $validator = new JValidator();
        $cnpjSemDV = '12ABC34501DE';
        $cnpjComDV = $cnpjSemDV . $validator->calculaDV($cnpjSemDV);
        $this->assertTrue($validator->isValid($cnpjComDV));
    }

    public function testCNPJComFormatacao()
    {
        $validator = new JValidator();
        $cnpjSemDV = '12.ABC.345/01DE';
        $cnpjComDV = $cnpjSemDV . '-' . $validator->calculaDV($cnpjSemDV);
        $this->assertTrue($validator->isValid($cnpjComDV));
    }
}
```
