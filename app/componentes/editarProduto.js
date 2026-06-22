  const editarProduto = (navigation, produto) => {
      navigation.navigate("Cadastro", {
          produto: produto
      });
  };

  export default editarProduto;