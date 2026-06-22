import { useEffect, useState } from "react";
import {
    FlatList,
    View,
    Button,
    Text,
    Alert
} from "react-native";
import { inAxios } from "../config_axios";
import Estilos from "../estilo/Estilos";
import editarEndereco from "./editarEndereco";
import excluirEndereco from "./excluirEndereco";


const ListaEndereco = ({navigation}) => {

    const [enderecos, setEnderecos] = useState([]);

    useEffect(() => {
        carregandoEnderecos();
    }, []);

    const carregandoEnderecos = async () => {
        try {
            const response = await inAxios.get(
                "/enderecoApi.php?action=listar"
            );

            setEnderecos(response.data);

        } catch (error) {
            console.log("Erro:", error);
        }
    };

    const excluirEndereco = async (id) => {
        Alert.alert(
            "Confirmação",
            "Deseja excluir este endereço?",
            [
                {
                    text: "Cancelar",
                    style: "cancel"
                },
                {
                    text: "Excluir",
                    onPress: async () => {
                        try {
                            const response = await inAxios.delete(
                                `/enderecoApi.php?action=excluir&id=${id}`
                            );

                            Alert.alert(
                                "Sucesso",
                                response.data.message || "Endereço excluído"
                            );

                            carregandoEnderecos();

                        } catch (error) {
                            Alert.alert(
                                "Erro",
                                error?.response?.data?.error ||
                                "Erro ao excluir endereço"
                            );
                        }
                    }
                }
            ]
        );
    };

    return (
        <View style={Estilos.container}>
            <Text style={Estilos.titulo}>
                Lista de Endereços
            </Text>

            <FlatList
                data={enderecos}
                keyExtractor={(item) => String(item.id)}
                renderItem={({ item }) => (
                    <View style={Estilos.card}>

                        <Text style={Estilos.texto}>
                            Logradouro: {item.logradouro}
                        </Text>

                        <Text style={Estilos.texto}>
                            Bairro: {item.bairro}
                        </Text>

                        <Text style={Estilos.texto}>
                            Cidade: {item.cidade}
                        </Text>

                        <Text style={Estilos.texto}>
                            UF: {item.uf}
                        </Text>

                        <Text style={Estilos.texto}>
                            Cliente: {item?.cliente?.nome || "Não informado"}
                        </Text>

                        <View style={Estilos.areaBotoes}>
                            <Button
                                title="Editar"
                                onPress={() =>
                                    navigation.navigate(
                                        "Endereco",
                                        { endereco: item }
                                    )
                                }
                            />

                            <Button
                                title="Excluir"
                                color="red"
                                onPress={() =>
                                    excluirEndereco(item.id)
                                }
                            />
                        </View>

                    </View>
                )}
            />
        </View>
    );
};

export default ListaEndereco;