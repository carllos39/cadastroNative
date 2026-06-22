import { Picker } from "@react-native-picker/picker";
import { useEffect, useState } from "react";
import { StyleSheet, View, Text, TextInput, Button, Alert, Image } from "react-native";
import { inAxios } from "../config_axios";
import Estilos from "../estilo/Estilos";
import ListaEndereco from "./ListaEndereco";

const Endereco = ({ route, navigation }) => {
    const endereco = route?.params?.endereco;

    const [id, setId] = useState(null);
    const [cep, setCep] = useState("");
    const [logradouro, setLogradouro] = useState("");
    const [bairro, setBairro] = useState("");
    const [cidade, setCidade] = useState("");
    const [estado, setEstado] = useState("");
    const [clienteId, setClienteId] = useState("");
    const [clientes, setClientes] = useState([]);

    async function buscarCep() {
        try {
            const cepLimpo = cep.replace(/\D/g, "");

            if (cepLimpo.length !== 8) {
                Alert.alert("Erro", "Digite um CEP válido com 8 números");
                return;
            }
            const response = await inAxios.get(
                `https://viacep.com.br/ws/${cep}/json/`
            );
            const dados = response.data;
            if (dados.erro) {
                Alert.alert("Erro", "Cep não encontrado");
                return;
            }
            setLogradouro(dados.logradouro);
            setBairro(dados.bairro);
            setCidade(dados.localidade);
            setEstado(dados.uf);
        } catch (error) {
            Alert.alert("Erro", "Falha ao consultar cep");
        }
    }

    useEffect(() => {
        carregarClientes();

        if (endereco) {
            setId(endereco.id);
            setLogradouro(endereco.logradouro);
            setBairro(endereco.bairro);
            setCidade(endereco.cidade);
            setEstado(endereco.estado);
            setClienteId(String(endereco.cliente_id));
        }
    }, []);

    const salvar = async () => {
        if (!logradouro || !bairro || !cidade || !estado || !clienteId) {
            Alert.alert(
                "Atenção",
                "Preencha todos os campos!"
            );
            return;
        }

        const dados = {
            logradouro,
            bairro,
            cidade,
            estado,
            cliente_id: clienteId
        };

        try {
            let response;

            if (id) {
                response = await inAxios.put(
                    `/enderecoApi.php?action=editar&id=${id}`,
                    dados
                );
            } else {
                response = await inAxios.post(
                    "/enderecoApi.php?action=cadastrar",
                    dados
                );
            }

            const retorno = response.data;

            if (retorno.success) {
                Alert.alert(
                    "Sucesso",
                    retorno.message
                );
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
        }
    };

    const carregarClientes = async () => {
        try {
            const response = await inAxios.get(
                "/clienteApi.php?action=listar"
            );

            setClientes(response.data);

        } catch (erro) {
            console.log("Erro:", erro);
        }
    };

    return (
        <View style={Estilos.container}>
            <Text style={Estilos.titulo}>Endereços</Text>
            <Image style={Estilos.imagem}
                source={require('../assets/marcas.webp')} />
            <TextInput style={Estilos.input}
                placeholder="Digite o cep"
                value={cep}
                onChangeText={setCep}
                keyboardType="numeric" />
            <Button
                title="Buscar Cep"
                onPress={buscarCep} />
            <TextInput style={Estilos.input}
                placeholder="Logradouro"
                value={logradouro}
                onChangeText={setLogradouro}
            />

            <TextInput style={Estilos.input}
                placeholder="Bairro"
                value={bairro}
                onChangeText={setBairro}
            />

            <TextInput style={Estilos.input}
                placeholder="Cidade"
                value={cidade}
                onChangeText={setCidade}
            />

            <TextInput style={Estilos.input}
                placeholder="Estado"
                value={estado}
                onChangeText={setEstado}
            />

            <Picker style={Estilos.picker}
                selectedValue={clienteId}
                onValueChange={(itemValue) =>
                    setClienteId(String(itemValue))
                }
            >
                <Picker.Item
                    label="Selecione um Cliente"
                    value=""
                />

                {clientes.map((cliente) => (
                    <Picker.Item
                        key={String(cliente.id)}
                        label={cliente.nome}
                        value={String(cliente.id)}
                    />
                ))}
            </Picker>
            <View style={Estilos.areaBotoes}>
                <Button
                    title={id ? "Atualizar" : "Cadastrar"}
                    onPress={() => salvar()}
                />
                <Button
                    title="Lista de Endereço"
                    onPress={() => navigation.navigate("ListaEndereco")}
                />
            </View>
        </View>
    );
};

export default Endereco;