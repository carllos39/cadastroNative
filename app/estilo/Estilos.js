import { Picker } from "@react-native-picker/picker";
import { StyleSheet } from "react-native"

export default StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#bdd1de',
    alignItems: 'center',
    justifyContent: 'center',
  },
  titulo: {
    fontSize: 24,
    textAlign: "center",
    marginBottom: 20,
  },
  input: {
    borderWidth: 1,
    marginBottom: 10,
    padding: 10,
    borderRadius: 5,
    width:400
  },
  picker:{
    height:50,
    width:"100%"
  },
  imagem:{
    height:350,
    width:350,
    marginBottom:30,
   borderRadius: 4,
  },
    lista: {
    width: "100%",
    marginTop: 10,
  },

  card: {
    backgroundColor: "#FFF",
    padding: 15,
    marginVertical: 5,
    borderRadius: 10,
    elevation: 3,
},

texto: {
    fontSize: 16,
    marginBottom: 5,
},

areaBotoes: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginTop: 10,
},
});