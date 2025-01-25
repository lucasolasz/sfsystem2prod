<?php

class VeiculosUtil
{

    public static function recuperarVeiculosFormulario($formulario)
    {

        $listaVeiculosSaida = [];

        // Iterar pelos índices dos veículos
        foreach ($formulario as $key => $value) {
            // Verifica se a chave contém o prefixo "tipo_veiculo_"
            if (strpos($key, 'tipo_veiculo_') === 0) {
                // Extrair o número do veículo a partir da chave
                $index = str_replace('tipo_veiculo_', '', $key);

                // Verificar se existem os campos correspondentes para "placa" e "cor"
                if (isset($formulario["placa_veiculo_$index"]) && isset($formulario["cor_veiculo_$index"])) {
                    // Criar um array associativo para o veículo
                    $listaVeiculosSaida[] = [
                        'fk_tipo_veiculo' => $formulario["tipo_veiculo_$index"],
                        'ds_placa_veiculo' => $formulario["placa_veiculo_$index"],
                        'fk_cor_veiculo' => $formulario["cor_veiculo_$index"]
                    ];
                }
            }
        }

        return $listaVeiculosSaida;
    }
}
