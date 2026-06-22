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

            setProdutos(dados);

        } catch (erro) {
            console.log("Erro:", erro);
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
    keyExtractor={(item) => String(item.id)}
    renderItem={({ item }) => (
        <View style={Estilos.card}>
            <Text style={Estilos.texto}>
                Nome: {item.nome}
            </Text>

            <Text style={Estilos.texto}>
                Preço: R$ {item.preco}
            </Text>

            <Text style={Estilos.texto}>
                Validade: {item.data_validade}
            </Text>

            <Text style={Estilos.texto}>
                Cliente: {item.cliente.nome}
            </Text>

            <View style={Estilos.areaBotoes}>
            <Button
            title="Editar"
            onPress={() => editarProduto(navigation, item)}
              />

            <Button
                title="Excluir"
            color="red"
             onPress={() => excluirProduto(item.id, carregarProdutos)}
            />
            </View>
        </View>
    )}
/>

        </View>
    );
};

export default Lista;