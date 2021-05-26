import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { WizardAddressComponent } from './wizard-address/wizard-address.component';
import { WizardKeyComponent } from './wizard-key/wizard-key.component';
import { WizardPasswordComponent } from './wizard-password/wizard-password.component';
import { WizardPermissionComponent } from './wizard-permission/wizard-permission.component';
import { LoginComponent } from './login/login.component';



@NgModule({
  declarations: [
    WizardAddressComponent,
    WizardKeyComponent,
    WizardPasswordComponent,
    WizardPermissionComponent,
    LoginComponent
  ],
  imports: [
    CommonModule
  ]
})
export class StartModule { }
