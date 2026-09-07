import { useEffect, useState } from "react";
import { View, Text, Button, FlatList } from "react-native";
import { inAxios } from "../config_axios";
import Estilos from "../estilo/Estilos";
import editarProduto from "./editarProduto";
import excluirProduto from "./excluirProduto";

const Lista = ({ navigation }) => {
    const [produtos, setProdutos] = useState([]);

    useEffect(() => {
        carregarProdutos();
    }, []);

    const carregarProdutos = async () => {
        try {
            const response = await inAxios.get(
                "/produtoApi.php?action=listar"
            );

            const dados = response.data;

            console.log("Produtos recebidos:", dados);

            // Se a API retornar um array diretamente
            if (Array.isArray(dados)) {
                setProdutos(dados);
            }
            // Se a API retornar { success: true, produtos: [...] }
            else if (
                dados?.success &&
                Array.isArray(dados.produtos)
            ) {
                setProdutos(dados.produtos);
            }
            else {
                console.log("A API não retornou uma lista de produtos.");
                setProdutos([]);
            }

        } catch (erro) {
            console.log(
                "Erro ao carregar produtos:",
                erro.response?.data || erro.message
            );
        }
    };

    return (
        <View style={Estilos.container}>

            <Text style={Estilos.titulo}>
                Lista de Produtos
            </Text>

            <FlatList
                style={Estilos.lista}
                data={produtos}
                keyExtractor={(item, index) =>
                    String(item.id ?? index)
                }
                ListEmptyComponent={
                    <Text style={Estilos.texto}>
                        Nenhum produto encontrado.
                    </Text>
                }
                renderItem={({ item }) => (

                    <View style={Estilos.card}>

                        <Text style={Estilos.texto}>
                            Nome: {item.nome ?? item.descricao ?? ""}
                        </Text>

                        <Text style={Estilos.texto}>
                            Preço: R$ {item.preco ?? item.valor ?? "0,00"}
                        </Text>

                        <Text style={Estilos.texto}>
                            Validade: {item.data_validade ?? ""}
                        </Text>

                        <Text style={Estilos.texto}>
                            Cliente: {item.cliente?.nome ?? "Não informado"}
                        </Text>

                        <View style={Estilos.areaBotoes}>

                            <Button
                                title="Editar"
                                onPress={() =>
                                    editarProduto(navigation, item)
                                }
                            />

                            <Button
                                title="Excluir"
                                color="red"
                                onPress={() =>
                                    excluirProduto(item.id, carregarProdutos)
                                }
                            />

                        </View>

                    </View>

                )}
            />

        </View>
    );
};

export default Lista;