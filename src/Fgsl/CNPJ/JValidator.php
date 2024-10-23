<?php
namespace Fgsl\CNPJ;
/**
 * Fgsl CNPJ
 * 
 * Esta classe foi baseada na implementação da classe CNPJValidator do Serpro
 *
 * @author    Flávio Gomes da Silva Lisboa <flavio.lisboa@fgsl.eti.br>
 * @link      https://github.com/fgsl/cnpj for the canonical source repository
 * @copyright Copyright (c) 2024 FGSL (http://www.fgsl.eti.br)
 * @license   https://www.gnu.org/licenses/agpl.txt  GPL-3.0 license
 */
class JValidator {
    const TAMANHO_CNPJ_SEM_DV = 12;
    const REGEX_CARACTERES_FORMATACAO = "[.\/-]";
    const REGEX_FORMACAO_BASE_CNPJ = "[A-Z\\d]{12}";
    const REGEX_FORMACAO_DV = "[\\d]{2}";
    const REGEX_VALOR_ZERADO = "^[0]+$";
    const VALOR_BASE = 0;
    const PESOS_DV = [ 6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2 ];

    public function isValid(string $cnpj): bool
    {
        if ($cnpj != null) {
            $cnpj = $this->removeCaracteresFormatacao($cnpj);
            if ($this->isCnpjFormacaoValidaComDV($cnpj)) {
                $dvInformado = substr($cnpj,self::TAMANHO_CNPJ_SEM_DV);
                $dvCalculado = $this->calculaDV(substr($cnpj,0, self::TAMANHO_CNPJ_SEM_DV));
                return $dvCalculado === $dvInformado;
            }
        }
        return false;
    }
    public function calculaDV(string $baseCnpj): string {
        if ($baseCnpj != null) {
            $baseCnpj = $this->removeCaracteresFormatacao($baseCnpj);
            if ($this->isCnpjFormacaoValidaSemDV($baseCnpj)) {
                $dv1 = (string) $this->calculaDigito($baseCnpj);
                $dv2 = (string) $this->calculaDigito($baseCnpj . $dv1);
                return $dv1 . $dv2;
            }
        }
        throw new \InvalidArgumentException("CNPJ $baseCnpj não é válido para o cálculo do DV");
    }

    private function calculaDigito(string $cnpj): int {
        $soma = 0;
        for ($indice = strlen($cnpj) - 1; $indice >= 0; $indice--) {
            $valorCaracter = (int) $cnpj[$indice] - self::VALOR_BASE;
            $soma += $valorCaracter * self::PESOS_DV[count(self::PESOS_DV) - strlen($cnpj) + $indice];
        }
        return $soma % 11 < 2 ? 0 : 11 - ($soma % 11);
    }

    private function removeCaracteresFormatacao(string $cnpj): string {
        return preg_replace('/'. self::REGEX_CARACTERES_FORMATACAO . '/', "",trim($cnpj));
    }
    
    private function isCnpjFormacaoValidaSemDV(string $cnpj): bool {
        return ((bool) preg_match('/'. self::REGEX_FORMACAO_BASE_CNPJ . '/',$cnpj)) && !preg_match('/'. self::REGEX_VALOR_ZERADO . '/' ,$cnpj);
    }

    private function isCnpjFormacaoValidaComDV(string $cnpj): bool {
        return ((bool) preg_match('/'. self::REGEX_FORMACAO_BASE_CNPJ . self::REGEX_FORMACAO_DV . '/',$cnpj)) &&
        !preg_match('/' . self::REGEX_VALOR_ZERADO . '/',$cnpj);
    }
}