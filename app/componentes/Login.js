import { useState } from "react";
import { View, Text, Button, TextInput, Alert, Image } from "react-native";
import Estilos from "../estilo/Estilos";
import {inAxios} from "../config_axios";

const Login = ({ navigation }) => {
  const [email, setEmail] = useState("");
  const [senha, setSenha] = useState("");

  const logar = async () => {
    try {
      const formData = new FormData();
      formData.append("email", email);
      formData.append("senha", senha);

      const response = await inAxios.post(
        "/clienteApi.php?action=logar",
        formData,
        
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      );

      const dados = response.data;

      if (dados.success) {
        Alert.alert(
          "Sucesso",
          dados.message,
          [
            {
              text: "OK",
              onPress: () => navigation.navigate("Cadastro"),
            },
          ]
        );

        console.log(dados.cliente);
      } else {
        Alert.alert(
          "Erro",
          dados.error || "Login inválido"
        );
      }
    } catch (erro) {
      console.log("Erro Axios:", erro.response?.data || erro.message);

      Alert.alert(
        "Erro",
        "Não foi possível conectar à API"
      );
    }
  };

  return (
    <View style={Estilos.container}>
      <Text style={Estilos.titulo}>Login</Text>

      <Image
        style={Estilos.imagem}
        source={require("../assets/marcas.webp")}
      />

      <TextInput
        style={Estilos.input}
        placeholder="Email"
        value={email}
        onChangeText={setEmail}
      />

      <TextInput
        style={Estilos.input}
        placeholder="Senha"
        secureTextEntry
        value={senha}
        onChangeText={setSenha}
      />

      <Button
        title="Logar"
        onPress={logar}
      />
    </View>
  );
};

export default Login;