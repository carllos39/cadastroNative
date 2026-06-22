import { Alert } from "react-native";
import { inAxios } from "../config_axios";


const excluirEndereco = async (id,carregandoEnderecos)=>{
   try{
 await inAxios.delete(
    `/enderecoApi.php?action=excluir&id=${id}`
 );
 Alert.alert(
    "Sucesso",
    "Endereço excluido com sucesso!"
 );
 carregandoEnderecos();
   }catch(error){
console.log("Erro ao excluir",error);
   }
};
export default excluirEndereco;