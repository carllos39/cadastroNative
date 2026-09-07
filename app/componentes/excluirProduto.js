

import { Alert } from "react-native";
import { inAxios } from "../config_axios";

const excluirProduto = async (id, carregarProdutos) => {
    try {
        await inAxios.delete(
            `produtoApi.php?action=excluir&id=${id}`
        );

        Alert.alert(
            "Sucesso",
            "Produto excluído com sucesso!"
        );

        carregarProdutos();

    } catch (erro) {
        console.log("Erro ao excluir:", erro);
    }
};

export default excluirProduto;