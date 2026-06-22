import { useEffect, useState } from "react";
import { Picker } from "@react-native-picker/picker";
import { StyleSheet, View, Text, Alert, TextInput, Button, Image } from "react-native";
import Estilos from "../estilo/Estilos";
import { inAxios } from "../config_axios";
import Endereco from "./Endereco.js";


const Cadastro = ({ route, navigation }) => {
    const produto = route?.params?.produto;

    const [id, setId] = useState(null);
    const [nome, setNome] = useState("");
    const [preco, setPreco] = useState("");
    const [data_validade, setData_validade] = useState("");
    const [clientes, setClientes] = useState([]);
    const [clienteId, setClienteId] = useState("");



    useEffect(() => {
        carregarClientes();

        if (produto) {
            setId(produto.id);
            setNome(produto.nome);
            setPreco(String(produto.preco));
            setData_validade(produto.data_validade);
            setClienteId(produto.cliente_id);
        }
    }, []);





    const salvar = async () => {

        if (!nome || !preco || !data_validade || !clienteId) {
            Alert.alert(
                "Atenção",
                "Preencha todos os campos."
            );
            return;
        }

        const dados = {
            nome,
            preco,
            data_validade,
            cliente_id: clienteId
        };

        try {

            let response;

            if (id) {

                response = await inAxios.put(
                    `/produtoApi.php?action=editar&id=${id}`,
                    dados
                );

            } else {

                response = await inAxios.post(
                    "/produtoApi.php?action=cadastrar",
                    dados
                );
            }

            const retorno = response.data;

            if (retorno.success) {

                Alert.alert(
                    "Sucesso",
                    retorno.message
                );

                navigation.navigate("Lista");

            } else {

                Alert.alert(
                    "Erro",
                    retorno.error || retorno.message
                );
            }

        } catch (erro) {

            const mensagem =
                erro?.response?.data?.error ||
                erro?.response?.data?.message ||
                "Falha ao comunicar com a API.";

            Alert.alert(
                "Erro",
                mensagem
            );

            console.log(erro.response?.data);
        }
    };


    useEffect(() => {
        carregarClientes();
    }, []);

    const carregarClientes = async () => {
        try {
            const response = await inAxios.get(
                "/clienteApi.php?action=listar",

            );
            const dados = response.data;
            //console.log(dados);
            setClientes(dados);
        } catch (erro) {
            console.log("ERRO:", erro);
        }
    };





    return (
        <View style={Estilos.container}>
            <Text style={Estilos.titulo}>Cadastro de Produtos</Text>
            <Image style={Estilos.imagem}
                source={require('../assets/marcas.webp')} />
            <TextInput style={Estilos.input}
                placeholder="Produto"
                value={nome}
                onChangeText={setNome} />
            <TextInput style={Estilos.input}
                placeholder="Preço"
                value={preco}
                keyboardType="numeric"
                onChangeText={setPreco} />
            <TextInput style={Estilos.input}
                placeholder="Data Validade (aaaa-mm-dd)"
                value={data_validade}
                onChangeText={setData_validade} />
            <Picker style={Estilos.picker}
                selectedValue={clienteId}
                onValueChange={(itemValue) =>
                    setClienteId(String(itemValue))
                }
            >
                <Picker.Item
                    label="Selecione um cliente"
                    value=""
                />

                {clientes.map((cliente) => (
                    <Picker.Item
                        key={String(cliente.id)}
                        label={String(cliente.nome)}
                        value={String(cliente.id)}
                    />
                ))}
            </Picker>
            <View style={Estilos.areaBotoes}>
                <Button
                    title={id ? "Atualizar" : "Cadastrar"}
                    onPress={salvar} />
                <Button
                    title="Lista"
                    onPress={() => navigation.navigate('Lista')} />
                <Button
                    title="Endereço"
                    onPress={() => navigation.navigate('Endereco')}
                />

            </View>
        </View>
    );
};
export default Cadastro;