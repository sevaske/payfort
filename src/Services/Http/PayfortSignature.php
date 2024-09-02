<?php

namespace Sevaske\Payfort\Services\Http;

class PayfortSignature
{
    public function __construct(protected string $shaPhrase, protected string $shaType = 'sha256') {}

    public function calculateSignature(array $data): string
    {
        ksort($data);
        $shaString = $this->shaPhrase.$this->implodeParamsToString($data).$this->shaPhrase;

        return hash($this->shaType, $shaString);
    }

    /**
     * This function appends all array elements one after the other,
     * differently based on their type
     * (products and apple have special concatenation agreements)
     *
     * @param  array  $arrayData  The array based on which the SHA string is calculated
     */
    private function implodeParamsToString(array $arrayData): string
    {
        $shaString = '';

        foreach ($arrayData as $index => $value) {
            $shaString .= match ($index) {
                'apple_header', 'apple_paymentMethod' => $index.'={'.$this->getAppleShaString($value).'}',
                'installment_detail' => '',
                default => $index.'='.$value,
            };
        }

        return $shaString;
    }

    /**
     * Special handling helper for Apple data
     */
    private function getAppleShaString(array $appleParams): string
    {
        $appleShaString = '';

        foreach ($appleParams as $index => $value) {
            if ($appleShaString) {
                $appleShaString .= ', ';
            }

            $appleShaString .= $index.'='.$value;
        }

        return $appleShaString;
    }
}
