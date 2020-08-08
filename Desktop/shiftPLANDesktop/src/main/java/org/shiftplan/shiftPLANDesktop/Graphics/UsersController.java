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

import com.jfoenix.controls.JFXButton;
import com.jfoenix.controls.JFXListView;
import com.jfoenix.controls.JFXTextField;
import javafx.event.Event;
import javafx.fxml.FXML;
import javafx.geometry.Pos;
import javafx.scene.control.Label;
import javafx.scene.layout.HBox;
import javafx.scene.layout.Pane;

public class UsersController {

    private boolean activeUsers = true;

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

    public void initialize() {
        for(int o = 0; o < 5; o++) {

            HBox hbox = new HBox();
            Label name = new Label(new User("Sascha" + o, 1).toString());
            JFXButton btn = new JFXButton("Hide");
            JFXButton btn2 = new JFXButton("Change");
            Pane pane = new Pane();

            name.setPrefWidth(400);
            name.setAlignment(Pos.CENTER_LEFT);
            btn.setPrefWidth(100);
            btn.setAlignment(Pos.CENTER);
            btn2.setPrefWidth(100);
            btn2.setAlignment(Pos.CENTER);
            pane.setPrefWidth(20);
            hbox.setAlignment(Pos.CENTER_LEFT);

            hbox.getChildren().addAll(name, btn2, pane, btn);

            UserList.getItems().add(hbox);

            btn.setOnAction(e -> UserList.getItems().remove(hbox));
        }
    }

    @FXML
    protected void OnActiveUsersButtonClick(Event event) {}

    @FXML
    protected void OnHiddenUsersButtonClick(Event event) {}

    @FXML
    protected void OnAddButtonClick(Event event) {}

}
