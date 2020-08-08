/*
 * UsersController.java
 *
 * Controller for Users.fxml.
 *
 * author: Sascha W.
 * last edit / by: 2020-08-08 / Sascha W.
 */
package org.shiftplan.shiftPLANDesktop.Graphics;

//Import statements

import com.jfoenix.controls.*;
import javafx.event.Event;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.geometry.Pos;
import javafx.scene.control.Label;
import javafx.scene.layout.AnchorPane;
import javafx.scene.layout.HBox;
import javafx.scene.layout.Pane;
import javafx.scene.layout.StackPane;

public class UsersController {

    private boolean activeUsers = true;

    private JFXDialog dialog;

    @FXML
    protected JFXButton ActiveUsersButton;

    @FXML
    protected JFXButton HiddenUsersButton;

    @FXML
    protected JFXTextField UserSearchBar;

    @FXML
    protected JFXListView UserList;

    @FXML
    protected JFXButton AddButton;

    @FXML
    protected StackPane UserStackPane;

    @FXML
    protected AnchorPane AnchorPaneColor;

    @FXML
    protected JFXButton SearchUserButton;

    public void initialize() {
        for(int o = 0; o < 20; o++) {

            HBox hbox = new HBox();
            Label name = new Label(new User("Sascha" + o, 1).toString());
            JFXButton btn = new JFXButton("Hide");
            JFXButton btn2 = new JFXButton("Change");
            Pane pane = new Pane();

            name.setPrefWidth(390);
            name.setAlignment(Pos.CENTER_LEFT);
            btn.setPrefWidth(100);
            btn.setStyle("-fx-background-color: #BBBDC0; -fx-border-color: #676262; -fx-background-radius: 5; -fx-border-radius: 5;");
            btn.setAlignment(Pos.CENTER);
            btn2.setPrefWidth(100);
            btn2.setStyle("-fx-background-color: #BBBDC0; -fx-border-color: #676262; -fx-background-radius: 5; -fx-border-radius: 5;");
            btn2.setAlignment(Pos.CENTER);
            pane.setPrefWidth(20);
            hbox.setAlignment(Pos.CENTER_LEFT);

            hbox.getChildren().addAll(name, btn2, pane, btn);

            UserList.getItems().add(hbox);

            btn.setOnAction(e -> UserList.getItems().remove(hbox));


        }
    }

    @FXML
    protected void OnActiveUsersButtonClick(Event event) {
        activeUsers = true;
        ActiveUsersButton.setStyle("-fx-background-color: #5D9FF5; -fx-background-radius: 0;");
        HiddenUsersButton.setStyle("-fx-background-color: #F9DAA0; -fx-background-radius: 0;");
        AnchorPaneColor.setStyle("-fx-background-color: #5D9FF5;");
        SearchUserButton.setStyle("-fx-background-color: #2F64A8;");
        AddButton.setStyle("-fx-background-color:  orange; -fx-background-radius: 60");
    }

    @FXML
    protected void OnHiddenUsersButtonClick(Event event) {
        activeUsers = false;
        ActiveUsersButton.setStyle("-fx-background-color: #9DC5F8; -fx-background-radius: 0;");
        HiddenUsersButton.setStyle("-fx-background-color: #F5C162; -fx-background-radius: 0;");
        AnchorPaneColor.setStyle("-fx-background-color: #F5C162;");
        SearchUserButton.setStyle("-fx-background-color: #A8781E;");
        AddButton.setStyle("-fx-background-color: #2F64A8; -fx-background-radius: 60");
    }

    @FXML
    protected void OnAddButtonClick(Event event) {
        try {
            FXMLLoader stackPaneLoader = new FXMLLoader(getClass().getResource("/AddUser.fxml"));

            dialog = new JFXDialog(UserStackPane, stackPaneLoader.load(), JFXDialog.DialogTransition.CENTER);

            stackPaneLoader.<AddUserController>getController().setParentController(this);

            dialog.show();
        }catch(Exception e) {}
    }

    @FXML
    protected void OnSearchUserButtonClick(Event event) {}

    public void closeDialog() {
        dialog.close();
    }
}
