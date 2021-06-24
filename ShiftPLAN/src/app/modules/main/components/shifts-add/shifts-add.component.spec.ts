/**
 * shifts-add.component.spec
 * 
 * Typescript class containing the tests for the 
 * shifts-add component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-24 / Anne Naumann
 */
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ShiftsAddComponent } from './shifts-add.component';

describe('ShiftsAddComponent', () => {
  let component: ShiftsAddComponent;
  let fixture: ComponentFixture<ShiftsAddComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ShiftsAddComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ShiftsAddComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
