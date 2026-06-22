import { StatusBar } from 'expo-status-bar';
import { StyleSheet, View } from 'react-native';
import Estilos from './estilo/Estilos';
import Login from './componentes/Login.js';

import { NavigationContainer } from "@react-navigation/native";
import { createNativeStackNavigator } from "@react-navigation/native-stack";
import Cadastro from './componentes/Cadastro.js';
import Lista from './componentes/Lista.js';
import Endereco from './componentes/Endereco.js';
import ListaEndereco from './componentes/ListaEndereco.js';
const Stack = createNativeStackNavigator();

export default function App() {
  return (
    <NavigationContainer>
      <Stack.Navigator initialRouteName="Login">
        <Stack.Screen
          name="Login"
          component={Login}
        />

        <Stack.Screen
          name="Cadastro"
          component={Cadastro}
        />
          <Stack.Screen
                    name="Lista"
                    component={Lista}
                />
                <Stack.Screen
                name="Endereco"
                component={Endereco}
                />
                <Stack.Screen
                name="ListaEndereco"
                component={ListaEndereco}
                />
      </Stack.Navigator>
    </NavigationContainer>
  );
}


