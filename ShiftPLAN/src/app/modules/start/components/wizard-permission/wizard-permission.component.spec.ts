/**
 * wizard-permission.component.spec
 * 
 * Typescript class containing the tests for the 
 * wizard-permission component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-05-26 / Anne Naumann
 */
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { WizardPermissionComponent } from './wizard-permission.component';

describe('WizardPermissionComponent', () => {
  let component: WizardPermissionComponent;
  let fixture: ComponentFixture<WizardPermissionComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ WizardPermissionComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(WizardPermissionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
