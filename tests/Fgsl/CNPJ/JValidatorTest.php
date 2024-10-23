<?php
use PHPUnit\Framework\TestCase;
use Fgsl\CNPJ\JValidator;
use PHPUnit\Framework\Attributes\CoversClass;
/**
 * Fgsl CNPJ
 *
 * @author    Flávio Gomes da Silva Lisboa <flavio.lisboa@fgsl.eti.br>
 * @link      https://github.com/fgsl/cnpj for the canonical source repository
 * @copyright Copyright (c) 2024 FGSL (http://www.fgsl.eti.br)
 * @license   https://www.gnu.org/licenses/agpl.txt  GPL-3.0 license
 */
#[CoversClass(JValidator::class)]
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